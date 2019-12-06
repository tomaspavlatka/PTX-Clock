<?php
namespace PTX;

class Clock {

    /**
     * Holds canvas.
     * @var
     */
    private $_canvas;

    /**
     * Holds settings for canvas.
     * @var
     */
    private $_canvas_params;

    /**
     * @var
     */
    private $_basePath;

    /**
     * @var null
     */
    private $_hour = null;

    /**
     * @var null
     */
    private $_min = null;

    /**
     * Initiate the class.
     * @param string $time - requested time, e.g. 10:25
     *
     * @throws ClockException
     */
    public function __construct($time)
    {
        $this->_basePath = dirname(dirname(dirname(__FILE__)));

        // Must be valid time.
        if(!preg_match('/^(0?[1-9]|1[0-2]):([0-5]?[0-9])$/', $time)) {
            throw new ClockException("Time {$time} is not a valid time.");
        }

        // Set private variables.
        list($hour, $min) = explode(':', $time);
        $this->_hour = sprintf('%02d', $hour);
        $this->_min = sprintf('%02d', $min);
    }

    /**
     * Draws a clock.
     *
     * @param array $params
     * @throws ClockException
     */
    public function draw(array $params = array())
    {
        // 1. Get canvas.
        $canvas_params = array_key_exists('canvas', $params) ? $params['canvas'] : array();
        $this->_get_canvas($canvas_params);

        // 2. Draw line for hour.
        $this->_draw_hour();

        // 3. Draw line for minute.
        $this->_draw_min();
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
        if(empty($this->_canvas)) {
            throw new ClockException("I cannot find the image.");
        }

        header("Content-type: image/png");
        imagepng($this->_canvas);
    }

    /**
     * Returns the image resource;
     *
     * @return resource
     * @throws ClockException
     */
    public function get_image_resource(){
        if(empty($this->_canvas)) {
            throw new ClockException("I cannot find the image.");
        }
        return $this->_canvas;
    }

    /**
     * Draws a line for hour.
     */
    private function _draw_hour()
    {
        // 1. Find coordinates.
        $angle = $this->_hour * 30 - 90;
        $angle += $this->_min * 0.5; // Adds effect for minutes.
        $coordinates = $this->_get_coordinates($angle, 5.5);

        // 2. Get canvas center.
        $canvas_center = $this->_canvas_params['canvas']['center'];

        // 4. Draw the line.
        $color = $this->_get_color('black');
        imageline($this->_canvas, $canvas_center['x'], $canvas_center['y'], $coordinates['x'], $coordinates['y'], $color);
    }

    /**
     * Draws a line for minute.
     */
    private function _draw_min()
    {
        // 1. Find coordinates.
        $angle = $this->_min * 6 - 90;
        $coordinates = $this->_get_coordinates($angle, 3);

        // 2. Get canvas center.
        $canvas_center = $this->_canvas_params['canvas']['center'];

        // 4. Draw the line.
        $color = $this->_get_color('black');
        imageline($this->_canvas, $canvas_center['x'], $canvas_center['y'], $coordinates['x'], $coordinates['y'], $color);
    }

    /**
     * Prepares canvas for the further use.
     *
     * @param array $params - additional params for canvas.
     *
     * @throws ClockException
     */
    private function _get_canvas(array $params = array())
    {
        // Save canvas to a file.
        $cachedCanvasFileName = 'canvas_'.md5(json_encode($params)).'.png';
        $cachedCanvasPath = $this->_basePath.'/cache/'.$cachedCanvasFileName;

        $canvas = new ClockCanvas($params);

        if(!file_exists($cachedCanvasPath)) {
            $canvas->draw();
            $canvas->to_file($cachedCanvasPath);
        }

        if(!file_exists($cachedCanvasPath)) {
            throw new ClockException("Could not load canvas for the clock.");
        }

        $this->_canvas = imagecreatefrompng($cachedCanvasPath);
        $this->_canvas_params = $canvas->get_params();
    }

    /**
     * Returns coordinates.
     *
     * @param float $angle
     * @param float $radius_divider
     *
     * @return array - x, y
     */
    private function _get_coordinates($angle, $radius_divider = '2.5')
    {
        $circle = $this->_canvas_params['circle_main'];
        $canvas_center = $this->_canvas_params['canvas']['center'];

        $radius = $circle['size'] / $radius_divider;
        $angle_radian = deg2rad($angle);
        $x = $radius * cos($angle_radian) + $canvas_center['x'];
        $y = $radius * sin($angle_radian) + $canvas_center['y'];

        $coordinates = array(
            'x' => (int)$x,
            'y' => (int)$y);

        return $coordinates;
    }

    /**
     * Returns actual value of $this->_hour.
     *
     * @return null|int
     */
    public function get_hour()
    {
        return $this->_hour;
    }

    /**
     * Returns actual value of $this->_min.
     *
     * @return null|int
     */
    public function get_min()
    {
        return $this->_min;
    }

    /**
     * Returns int of color.
     *
     * @param string|array $color - name of the color.
     *
     * @return int
     */
    private function _get_color($color)
    {
        if(is_array($color) === true && isset($color['r']) && isset($color['g']) && isset($color['b'])){
            return imagecolorallocate($this->_canvas, $color['r'], $color['g'], $color['b']);
        }
        
        switch($color) {
            case 'blue':
                return imagecolorallocate($this->_canvas, 25, 25, 112);
            case 'green':
                return imagecolorallocate($this->_canvas, 0, 100, 0);
            case 'red':
                return imagecolorallocate($this->_canvas, 255, 0, 0);
            case 'white':
                return imagecolorallocate($this->_canvas, 255, 255, 255);
            case 'yellow':
                return imagecolorallocate($this->_canvas, 255, 255, 0);
        }

        return 0;
    }
}