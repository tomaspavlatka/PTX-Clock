<?php

class ClockTest extends PHPUnit_Framework_TestCase {

    /**
     * Keeps object to Clock class.
     *
     * @var
     */
    private $Clock;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        unset($this->Clock);
        parent::tearDown();
    }

    /**
     * @param string $time
     * @param int $expected_hour
     * @param int $expected_min
     *
     * @dataProvider data_testInit_ValidTime_ProperData
     */
    public function testInit_ValidTime_ProperData($time, $expected_hour, $expected_min)
    {
        $this->init_object($time);

        $hour = $this->Clock->get_hour();
        $this->assertEquals($hour, $expected_hour);

        $hour = $this->Clock->get_min();
        $this->assertEquals($hour, $expected_min);
    }

    /**
     * @expectedException \PTX\ClockException
     */
    public function testInit_InvalidTime_Exception()
    {
        $this->init_object('14:25');

        $hour = $this->Clock->get_hour();
        $this->assertEquals($hour, 10);

        $hour = $this->Clock->get_min();
        $this->assertEquals($hour, 25);
    }

    public function data_testInit_ValidTime_ProperData() {
        return array(
            array('10:25', 10, 25),
            array('01:25', 01, 25),
            array('1:25', 01, 25),
            array('1:01', 01, 01),
            array('1:1', 01, 01),
        );
    }

    public function init_object(array $params = array())
    {
        $this->Clock = new \PTX\Clock($params);
    }
}