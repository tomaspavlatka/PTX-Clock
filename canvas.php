<?php

require_once './vendor/autoload.php';

try {
    $clock = new PTX\ClockCanvas();
    $clock->draw();
    $file_path = './cache/base.png';
    $clock->to_file($file_path);
    $clock->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
