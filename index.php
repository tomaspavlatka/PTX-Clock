<?php

require_once './vendor/autoload.php';

try {
    $clock = new PTX\Clock('09:55');
    $clock->draw();
    $clock->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
