<?php

require_once './vendor/autoload.php';

try {
    $base = new PTX\ClockCanvas();
    $base->draw();
    $base->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
