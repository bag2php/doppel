<?php

declare(strict_types=1);

namespace Bag2\StaticDouble\Replacer;

use Bag2\StaticDouble\Replacer;
use Bag2\StaticDouble\Util\CallableTrait;
use Closure;
use function extension_loaded;
use function runkit7_function_add;
use function runkit7_function_remove;
use function runkit7_function_rename;
use function runkit7_method_add;
use function runkit7_method_remove;
use function runkit7_method_rename;

/**
 * Method/Function Replacer implementation using runkit7
 *
 * @see https://github.com/runkit7/runkit7
 * @see https://www.php.net/manual/book.runkit7.php
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class Runkit7 implements Replacer
{
    use CallableTrait;

    public function __construct()
    {
        // noop
    }

    /**
     * @see https://www.php.net/runkit7_function_redefine
     * @see https://www.php.net/runkit7_method_redefine
     */
    public function register(?string $class_name, string $func_name, Closure $implementation): bool
    {
        $stashed_name = $this->stashMethodName($class_name ?? '', $func_name);

        if ($class_name === null) {
            runkit7_function_rename($func_name, $stashed_name);

            return runkit7_function_add($func_name, $implementation);
        }

        runkit7_method_rename($class_name, $func_name, $stashed_name);

        return runkit7_method_add($class_name, $func_name, $implementation);
    }

    /**
     * @see https://www.php.net/uopz_unset_return
     */
    public function restore(?string $class_name, string $func_name): bool
    {
        $stashed_name = $this->stashMethodName($class_name ?? '', $func_name);

        if ($class_name === null) {
            runkit7_function_remove($func_name);

            return runkit7_function_rename($stashed_name, $func_name);
        }

        runkit7_method_remove($class_name, $func_name);

        return runkit7_method_rename($class_name, $stashed_name, $func_name);
    }

    public static function enabled(): bool
    {
        return extension_loaded('runkit7');
    }
}
