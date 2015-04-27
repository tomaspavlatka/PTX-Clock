# PTX-Clock
PHP Class to create analog clock from the time given. I have a son and they are teaching him at school the clock.
I though it would be great to create a simple game to help him out. This class is just a beginning of the whole idea.

## Installation

You will need a composer to install autoload for you

```
composer install --no-dev
```

## Example.

```
require_once './vendor/autoload.php';

try {
    $clock = new PTX\Clock('09:55');
    $clock->draw();
    $clock->to_browser();
} catch(\PTX\ClockException $e) {
    echo $e->getMessage();
}
```

