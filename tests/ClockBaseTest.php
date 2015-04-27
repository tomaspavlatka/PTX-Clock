<?php

/**
 * Class ClockCanvasTest
 */
class ClockCanvasTest extends PHPUnit_Framework_TestCase {

    private $ClockCanvas;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        unset($this->ClockCanvas);
        parent::tearDown();
    }

    public function testInit_NoParams_CorrectCanvasCenter()
    {
        $this->init_object();
        $params = $this->ClockCanvas->get_params();
        $this->assertEquals(400, $params['width']);
        $this->assertEquals(400, $params['height']);

        $canvas = $params['canvas'];
        $canvas_center = $canvas['center'];
        $this->assertEquals(200, $canvas_center['x']);
        $this->assertEquals(200, $canvas_center['y']);
    }

    /**
     * @param array $params - params for the object
     * @param array $expected - expected result, check the function to
     * get better picture.
     *
     * @dataProvider data_testInit_ParamsWidthHeight_CorrectMainCircle
     */
    public function testInit_ParamsWidthHeight_CorrectMainCircle($params, $expected)
    {
        $this->init_object($params);
        $clock_params = $this->ClockCanvas->get_params();

        $circle_main = $clock_params['circle_main'];
        $this->assertEquals($expected['center']['x'], $circle_main['center']['x']);
        $this->assertEquals($expected['center']['y'], $circle_main['center']['y']);

        // Correct size.
        $this->assertEquals($expected['size'], $circle_main['size']);
    }

    public function testInit_NoParams_CorrectCenterCircle()
    {
        $this->init_object();
        $clock_params = $this->ClockCanvas->get_params();

        // Correct size.
        $circle_center = $clock_params['circle_center'];
        $this->assertEquals(10, $circle_center['size']);
    }

    /**
     * @param $params
     * @param $expected_canvas_center
     *
     * @dataProvider data_testInit_ParamsWidthHeight_CorrectCanvasCenter
     */
    public function testInit_ParamsWidthHeight_CorrectCanvasCenter($params, $expected_canvas_center)
    {
        $this->init_object($params);
        $clock_params = $this->ClockCanvas->get_params();
        $this->assertEquals($params['width'], $clock_params['width']);
        $this->assertEquals($params['height'], $clock_params['height']);

        $canvas = $clock_params['canvas'];
        $canvas_center = $canvas['center'];
        $this->assertEquals($expected_canvas_center['x'], $canvas_center['x']);
        $this->assertEquals($expected_canvas_center['y'], $canvas_center['y']);
    }

    public function data_testInit_ParamsWidthHeight_CorrectCanvasCenter()
    {
        return array(
            array(
                array('width' => 300, 'height' => 200),
                array('x' => 150, 'y' => 100)
            ),
            array(
                array('width' => 150, 'height' => 150),
                array('x' => 75, 'y' => 75)
            ),
            array(
                array('width' => 155, 'height' => 155),
                array('x' => 77, 'y' => 77)
            )
        );
    }

    public function data_testInit_ParamsWidthHeight_CorrectMainCircle()
    {
        return array(
            array(
                array('width' => 300, 'height' => 200),
                array(
                    'center' => array('x' => 150, 'y' => 100),
                    'size' => 180
                ),
            ),
            array(
                array('width' => 150, 'height' => 150),
                array(
                    'center' => array('x' => 75, 'y' => 75),
                    'size' => 135
                ),
            ),
            array(
                array('width' => 125, 'height' => 150),
                array(
                    'center' => array('x' => 62, 'y' => 75),
                    'size' => 112
                ),
            ),
        );
    }

    public function init_object(array $params = array())
    {
        $this->ClockCanvas = new \PTX\ClockCanvas($params);
    }
}