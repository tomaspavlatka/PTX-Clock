<?php

require_once '../vendor/autoload.php';

try {
    $time = isset($_GET['time']) ? $_GET['time'] : null;

    $params = array(
        'canvas' => array(
            'font' => '../fonts/instruction/instruction.ttf'));
    $clock = new PTX\Clock($time);
    $clock->draw($params);
    $clock->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
