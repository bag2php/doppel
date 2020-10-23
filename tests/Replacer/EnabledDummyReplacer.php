<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\Replacer;
use Closure;

/**
 * Dummy Replacer class for testing
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
final class EnabledDummyReplacer implements Replacer
{
    public function __construct()
    {
        // noop
    }

    /**
     * Register the class, method and implementation to replace
     */
    public function register(?string $class_name, string $func_name, Closure $implementation): bool
    {
        return true;
    }

    /**
     * Restore replaced methods
     */
    public function restore(?string $class_name, string $func_name): bool
    {
        return true;
    }

    /**
     * Check replacer module is available
     */
    public static function enabled(): bool
    {
        return true;
    }
}
