<?php

require_once './vendor/autoload.php';

try {
    $base = new PTX\ClockBase();
    $base->draw();
    $file_path = './cache/base.png';
    $base->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
