# Bag2\StaticDouble


**StaticDouble** is a PHP mocking framework for static methods.

## Example

```php
<?php

class Vehicle {
    public static function horn() {
        return 'Beep!';
    }
}

echo Vehicle::horn(), PHP_EOL; // Beep!

$static_double_manager = (new Bag2\StaticDouble\Factory)->create();
$static_double_manager->add('Vehicle::horn')->andReturn('Boo!');

echo Vehicle::horn(), PHP_EOL; // Boo!

$static_double_manager->finalize();

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
