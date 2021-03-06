<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Bag2\Doppel\Util\NumberHelper;
use Exception;

/**
 * Exception thrown if the method is called in an unexpected context
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class UnexpectedMethodCallException extends Exception
{
    use NumberHelper;

    /**
     * @phpstan-param 0|positive-int $expected_called_count
     * @phpstan-param positive-int $called_count
     */
    public static function generateMessageForCalledCount(
        string $func_name,
        int $expected_called_count,
        int $called_count,
        bool $finalized
    ): string {
        if ($expected_called_count === 0) {
            $suffix = $finalized
                ? ", but it was {$called_count} times called"
                : '';

            return "{$func_name}() is expected to never be called{$suffix}.";
        }

        if ($finalized) {
            $times = "{$called_count} times";
        } else {
            $nth = static::ordinal($called_count);
            $times = "the {$nth} time";
        }

        return "{$func_name}() is expected to be called {$expected_called_count} times,"
            . " but it was called {$times}.";
    }
}
