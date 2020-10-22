<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Closure;

/**
 * Default implementation of AlterFactory
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class DefaultFactory implements AlterFactory
{
    /**
     * @phpstan-template T
     * @param mixed $value
     * @phpstan-param T $value
     * @phpstan-return ReturnValueAlter<T>
     */
    public function fromFixedReturnValue($value): ReturnValueAlter
    {
        return new ReturnValueAlter(ReturnValueAlter::TYPE_FIXED, [$value]);
    }
}
