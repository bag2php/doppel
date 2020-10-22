# Bag2\Doppel


**Doppel** is a PHP mocking framework for static methods.

## Example

```php
<?php

class Vehicle {
    public static function horn() {
        return 'Beep!';
    }
}

echo Vehicle::horn(), PHP_EOL; // Beep!

$doppel = (new Bag2\Doppel\Factory)->create();
$doppel->add('Vehicle::horn')->andReturn('Boo!');

echo Vehicle::horn(), PHP_EOL; // Boo!

$doppel->finalize();

echo Vehicle::horn(), PHP_EOL; // Beep!
```

## Copyright

This package is licenced under [Mozilla Public License Version 2.0][MPL-2.0].

> Copyright 2020 Baguette HQ
>
> This Source Code Form is subject to the terms of the Mozilla Public
> License, v. 2.0. If a copy of the MPL was not distributed with this
> file, You can obtain one at <https://www.mozilla.org/en-US/MPL/2.0/>.

[MPL-2.0]: https://www.mozilla.org/en-US/MPL/2.0/
