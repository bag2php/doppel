<?php

declare(strict_types=1);

namespace Bag2\StaticDouble;

use Closure;

/**
 * The interface of method/function implementation replacer
 *
 * A class called "replacer" provides the feature to replace methods and functions.
 * It is typically just a wrapper around the procedure for making function calls and has no complex responsibilities.
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
interface Replacer
{
    public function __construct();

    /**
     * Register the class, method and implementation to replace
     */
    public function register(?string $class_name, string $func_name, Closure $implementation): bool;

    /**
     * Restore replaced methods
     */
    public function restore(?string $class_name, string $func_name): bool;

    /**
     * Check replacer module is available
     */
    public static function enabled(): bool;
}
