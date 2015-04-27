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

        $this->Clock = new \PTX\Clock();
    }

    protected function tearDown()
    {
        unset($this->Clock);
        parent::tearDown();
    }

    /**
     * @dataProvider data_testInit_ValidTime_ProperData
     */
    public function testInit_ValidTime_ProperData($time, $expected_hour, $expected_min)
    {
        $this->Clock->init($time);

        $hour = $this->Clock->getHour();
        $this->assertEquals($hour, $expected_hour);

        $hour = $this->Clock->getMin();
        $this->assertEquals($hour, $expected_min);
    }

    /**
     * @expectedException \PTX\ClockException
     */
    public function testInit_InvalidTime_Exception()
    {
        $this->Clock->init('14:25');

        $hour = $this->Clock->getHour();
        $this->assertEquals($hour, 10);

        $hour = $this->Clock->getMin();
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
}