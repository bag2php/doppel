<?php

declare(strict_types=1);

namespace Bag2\StaticDouble\Replacer;

use Bag2\StaticDouble\Replacer;
use Closure;
use function extension_loaded;
use function uopz_set_return;
use function uopz_unset_return;

/**
 * Method/Function Replacer implementation using uopz (User Operations for Zend)
 *
 * @see https://www.php.net/manual/book.uopz.php
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class Uopz implements Replacer
{
    public function __construct()
    {
        // noop
    }

    /**
     * @see https://www.php.net/uopz_set_return
     */
    public function register(?string $class_name, string $func_name, Closure $implementation): bool
    {
        if ($class_name === null) {
            return uopz_set_return($func_name, $implementation, true);
        }

        return uopz_set_return($class_name, $func_name, $implementation, true);
    }

    /**
     * @see https://www.php.net/uopz_unset_return
     */
    public function restore(?string $class_name, string $func_name): bool
    {
        if ($class_name === null) {
            return uopz_unset_return($func_name);
        }

        return uopz_unset_return($class_name, $func_name);
    }

    public static function enabled(): bool
    {
        return extension_loaded('uopz');
    }
}
