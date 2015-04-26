<?php
namespace PTX;

class ClockBase {

    private $_params = array(
        'width' => 400,
        'height' => 400
    );

    private $_image;

    public function __construct()
    {
    }

    public function init(array $params = array())
    {
        // 1. Merge params.
        $this->_params = array_merge($this->_params, $params);

        // 2. Do some preparation.
        $this->_prepare_params();
    }

    /**
     * Draws the clock.
     */
    public function draw_base() {
        // 1. Draw clock.
        $this->_draw_clock();

        // Send it to browser.
        $this->_sent_to_browser();
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
     * Draws main clock layout.
     *
     * @throws ClockException
     */
    private function _draw_clock()
    {
        // 1. Draw main circle.
        $this->_draw_circle('circle_main');

        // 2. Draw center circle.
        $this->_draw_circle('circle_center');

        // 3. Draw main number.
        $this->_draw_main_numbers();

        // 4. Draw minor numbers.
        $this->_draw_minor_numbers();
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
           throw new ClockException("Unknown data for $key - Did you call init() function?");
        }

        // 2. Find color.
        $circle_color = $this->_get_color($circle['color']);

        // 3. Draw circle.
        $center = $circle['center'];
        imagefilledellipse($this->_image, $center['x'] , $center['y'], $circle['size'], $circle['size'], $circle_color);
    }

    private function _draw_main_numbers()
    {
        $main_number = array(3, 6, 9, 12);

        foreach($main_number as $number) {
            $this->_draw_number($number, 20);
        }
    }

    private function _draw_minor_numbers()
    {
        $main_number = array(1, 2, 4, 5, 7, 8, 10, 11);

        foreach($main_number as $number) {
            $this->_draw_number($number, 10);
        }
    }

    private function _draw_number($number, $font_size)
    {
        $angle_part = 12 / $number;
        $angle = 360 / $angle_part - 90;
        $coordinates = $this->_get_number_coordinates($angle);

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

        $color = $this->_get_color('black');
        $font = './fonts/instruction/instruction.ttf';
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
     * Prepares base.
     */
    private function _prepare_base()
    {
        // 1. Set image
        $this->_image = imagecreatetruecolor($this->_params['width'], $this->_params['height']);

        // 2. Fill it with background color.
        imagecolorallocate($this->_image, 0, 0, 0);

        // 3. Prepare colors.
        $white = imagecolorallocate($this->_image, 255, 255, 255);
        $black = imagecolorallocate($this->_image, 0, 0, 0);
        $this->_params['colors'] = array(
            'white' => $white, 'black' => $black);
    }

    /**
     * Counts and stores into $this->_params information
     * about canvas.
     */
    private function _prepare_canvas()
    {
        $this->_params['canvas'] = array(
            'center' => $this->_get_canvas_center());
    }

    /**
     * Prepares information about circle,
     * which will be in the center
     */
    private function _prepare_center_circle()
    {
        $center = $this->_get_canvas_center();
        $this->_params['circle_center'] = array(
            'center' => $center,
            'color'  => 'black',
            'size'   => 10);
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
        $this->_prepare_base();
    }

    /**
     * Last step - sends to browser.
     */
    private function _sent_to_browser()
    {
        header("Content-type: image/png");
        imagepng($this->_image);
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
        if (array_key_exists($color, $this->_params['colors'])) {
            $color = $this->_params['colors'][$color];
            return (int)$color;
        }

        return 0;
    }


}