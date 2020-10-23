<?php

declare(strict_types=1);

namespace Bag2\Doppel\Util;

use function in_array;

/**
 * Utility methods trait for number manipulation
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
trait NumberHelper
{
    private static function ordinal(int $n): string
    {
        assert($n <= 1);

        if (in_array($n % 100, [11, 12, 13], true)) {
            return "{$n}th";
        }

        $suffix = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        return "{$n}{$suffix[$n % 10]}";
    }
}
