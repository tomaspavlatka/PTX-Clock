<?php
namespace PTX;

class ClockBase {

    /**
     * @var
     */
    private $_image;

    /**
     * Holds params for the class.
     *
     * @var array
     */
    private $_params = array(
        'width' => 400,
        'height' => 400,
        'background' => 'white',
        'canvas_background' => 'black',
        'major_numbers' => array(
            'color' => 'black', 'size' => 20),
        'minor_numbers' => array(
            'color' => 'black', 'size' => 10),
        'font' => './fonts/instruction/instruction.ttf');

    /**
     * Constructor of the class.
     *
     * @param array $params - possible to affect the
     * final result.
     */
    public function __construct(array $params = array())
    {
        // 1. Merge params.
        $this->_params = array_merge($this->_params, $params);

        // 2. Do some preparation.
        $this->_prepare_params();
    }

    /**
     * Cleans up.
     */
    public function __destruct()
    {
        // If we have an image, destroy it and free the memory
        if(!empty($this->_image)) {
            imagedestroy($this->_image);
        }
    }

    /**
     * Draws the clock.
     */
    public function draw()
    {
        // 1. Draw main circle.
        $this->_draw_circle('circle_main');

        // 2. Draw center circle.
        $this->_draw_circle('circle_center');

        // 3. Draw main number.
        $this->_draw_major_numbers();

        // 4. Draw minor numbers.
        $this->_draw_minor_numbers();
    }

    /**
     * Returns actual value of $this->_params;
     *
     * @return array
     */
    public function get_params()
    {
        return (array)$this->_params;
    }

    /**
     * Saves image into file path given.
     *
     * @param string $file_path
     * @throws ClockException
     */
    public function to_file($file_path)
    {
        if(empty($this->_image)) {
            throw new ClockException("I cannot find the image.");
        }

        if(!imagepng($this->_image, $file_path)) {
            throw new ClockException("I could not save image into $file_path.");
        }
    }

    /**
     * Sends result to browser.
     */
    public function to_browser()
    {
        if(empty($this->_image)) {
            throw new ClockException("I cannot find the image.");
        }

        header("Content-type: image/png");
        imagepng($this->_image);
    }

    /**
     * Prepares canvas.
     */
    private function _draw_canvas()
    {
        // 1. Set image
        $this->_image = imagecreatetruecolor($this->_params['width'], $this->_params['height']);

        // 2. Fill it with background color.
        $canvas = $this->_params['canvas'];
        $color = $this->_get_color($canvas['color']);
        imagefill($this->_image, 0, 0, $color);
    }

    /**
     * Draws circle into the image.
     *
     * @param string $key - name of the circle.
     *
     * @throws ClockException
     */
    private function _draw_circle($key = 'circle_main')
    {
        // 1. Get data about circle.
        $circle = $this->_params[$key];
        if(empty($circle)) {
           throw new ClockException("Unknown data for $key.");
        }

        // 2. Find color.
        $circle_color = $this->_get_color($circle['color']);

        // 3. Draw circle.
        $center = $circle['center'];
        imagefilledellipse($this->_image, $center['x'] , $center['y'], $circle['size'], $circle['size'], $circle_color);
    }

    /**
     * Draws main number into the clock.
     */
    private function _draw_major_numbers()
    {
        // 1. Find font settings.
        $font = $this->_params['major_numbers'];

        // 2. Draw number one by one.
        $major_numbers = array(3, 6, 9, 12);
        foreach($major_numbers as $number) {
            $this->_draw_number($number, $font['size'], $font['color']);
        }
    }

    /**
     * Draws minor numbers into the clock.
     */
    private function _draw_minor_numbers()
    {
        // 1. Find font settings.
        $font = $this->_params['minor_numbers'];

        // 2. Draw number one by one.
        $minor_numbers = array(1, 2, 4, 5, 7, 8, 10, 11);
        foreach($minor_numbers as $number) {
            $this->_draw_number($number, $font['size'], $font['color']);
        }
    }

    /**
     * Draws a single number into the clock.
     *
     * @param int $number
     * @param int $font_size
     * @param mixed $color - font color.
     */
    private function _draw_number($number, $font_size, $color)
    {
        // 1. Get coordinates.
        $angle_part = 12 / $number;
        $angle = 360 / $angle_part - 90; // coordinates starts on number 3
        $coordinates = $this->_get_number_coordinates($angle);

        // 2. Small corrections for better look.
        switch($number) {
            case 3:
            case 9:
                $coordinates['y'] += 10;
                break;
            case 6:
                $coordinates['x'] -= 6;
                break;
            case 12:
                $coordinates['x'] -= 11;
                break;
        }

        // 3. Draw.
        $font = $this->_params['font'];
        $color = $this->_get_color($color);
        imagefttext($this->_image, $font_size, 0, $coordinates['x'], $coordinates['y'], $color, $font, $number);
    }

    /**
     * Returns information about
     * canvas center.
     *
     * @return array
     */
    private function _get_canvas_center()
    {
        // 1. Try to get it from $this->_params
        if(array_key_exists('canvas', $this->_params)) {
           if(array_key_exists('center', $this->_params['canvas'])) {
               return (array)$this->_params['canvas']['center'];
           }
        }

        // 2. Count center.
        $center_x = (int) ($this->_params['width'] / 2);
        $center_y = (int) ($this->_params['height'] / 2);
        $center = array(
            'x' => $center_x, 'y' => $center_y);

        // 3. Return it.
        return (array)$center;
    }

    /**
     * Returns coordinates for a number.
     *
     * @param float $angle
     *
     * @return array - x, y
     */
    private function _get_number_coordinates($angle)
    {
        $circle = $this->_params['circle_main'];
        $canvas_center = $this->_params['canvas']['center'];

        $radius = $circle['size'] / 2.5;
        $angle_radian = deg2rad($angle);
        $x = $radius * cos($angle_radian) + $canvas_center['x'];
        $y = $radius * sin($angle_radian) + $canvas_center['y'];

        $coordinates = array(
            'x' => (int)$x,
            'y' => (int)$y);

        return $coordinates;
    }

    /**
     * Counts and stores into $this->_params information
     * about canvas.
     */
    private function _prepare_canvas()
    {
        $this->_params['canvas'] = array(
            'center' => $this->_get_canvas_center(),
            'color' => $this->_params['canvas_background']);
    }

    /**
     * Prepares information about circle,
     * which will be in the center
     */
    private function _prepare_center_circle()
    {
        $center = $this->_get_canvas_center();
        $this->_params['circle_center'] = array(
            'size'   => 10,
            'center' => $center,
            'color'  => $this->_params['major_numbers']['color']);
    }

    /**
     * Prepares information about main circle.
     *
     */
    private function _prepare_main_circle()
    {
        $center = $this->_get_canvas_center();

        $width = $this->_params['width'];
        $height = $this->_params['height'];

        if($width > $height) {
            $circle_width = floor($height * 0.9);
        } else {
            $circle_width = floor($width * 0.9);
        }

        $this->_params['circle_main'] = array(
            'center' => $center,
            'color'  => 'white',
            'size'   => $circle_width
        );
    }

    /**
     * Counts all necessary data for drawing
     */
    private function _prepare_params()
    {
        // 1. Find center of the canvas
        $this->_prepare_canvas();

        // 2. Big circle.
        $this->_prepare_main_circle();

        // 3. Center circle.
        $this->_prepare_center_circle();

        // 4. Prepare base.
        $this->_draw_canvas();
    }

    /**
     * Returns int of color.
     *
     * @param string $color - name of the color.
     *
     * @return int
     */
    private function _get_color($color)
    {
        switch($color) {
            case 'blue':
                return imagecolorallocate($this->_image, 25, 25, 112);
            case 'green':
                return imagecolorallocate($this->_image, 0, 100, 0);
            case 'red':
                return imagecolorallocate($this->_image, 255, 0, 0);
            case 'white':
                return imagecolorallocate($this->_image, 255, 255, 255);
            case 'yellow':
                return imagecolorallocate($this->_image, 255, 255, 0);
        }

        return 0;
    }


}