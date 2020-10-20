<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\Replacer;
use Bag2\Doppel\Util\CallableHelper;
use Closure;
use function extension_loaded;
use function uopz_add_function;
use function uopz_del_function;
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
    use CallableHelper;

    public function __construct()
    {
        // noop
    }

    /**
     * @see https://www.php.net/uopz_set_return
     */
    public function register(?string $class_name, string $func_name, Closure $implementation): bool
    {
        $stashed_name = $this->stashMethodName($class_name ?? '', $func_name);

        if ($class_name === null) {
            uopz_add_function($stashed_name, Closure::fromCallable($func_name));;

            return uopz_set_return($func_name, $implementation, true);
        }

        uopz_add_function($class_name, $stashed_name, Closure::fromCallable([$class_name, $func_name]));

        return uopz_set_return($class_name, $func_name, $implementation, true);
    }

    /**
     * @see https://www.php.net/uopz_unset_return
     */
    public function restore(?string $class_name, string $func_name): bool
    {
        $stashed_name = $this->stashMethodName($class_name ?? '', $func_name);

        if ($class_name === null) {
            uopz_del_function($stashed_name);

            return uopz_unset_return($func_name);
        }

        uopz_del_function($class_name, $stashed_name);

        return uopz_unset_return($class_name, $func_name);
    }

    public static function enabled(): bool
    {
        return extension_loaded('uopz');
    }
}
