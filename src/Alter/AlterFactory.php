<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Closure;

/**
 * A factory class of "Alter" (alternative implementation of function/method)
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
interface AlterFactory
{
    /**
     * @phpstan-template T
     * @param mixed $value
     * @phpstan-param T $value
     * @phpstan-return ReturnValueAlter<T>
     */
    public function fromFixedReturnValue($value): ReturnValueAlter;
}
