<?php
namespace PTX;

class Clock {

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
    public function init($time)
    {
        // Must be valid time.
        if(!preg_match('/^(0?[1-9]|1[0-2]):([0-5]?[0-59])$/', $time)) {
            throw new ClockException("Time {$time} is not a valid time.");
        }

        // Set private variables.
        list($hour, $min) = explode(':', $time);
        $this->_hour = sprintf('%02d', $hour);
        $this->_min = sprintf('%02d', $min);
    }

    /**
     * Returns actual value of $this->_hour.
     *
     * @return null|int
     */
    public function getHour()
    {
        return $this->_hour;
    }

    /**
     * Returns actual value of $this->_min.
     *
     * @return null|int
     */
    public function getMin()
    {
        return $this->_min;
    }
}