<?php

declare(strict_types=1);

namespace Bag2\StaticDouble\Util;

use function explode;
use function is_array;
use function is_string;
use LogicException;

/**
 * Utility methods trait for callable manipulation
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
trait CallableTrait
{
    /**
     * Extract class-name and method-name from callable value.
     *
     * @return array{0:?string,1:string}
     */
    protected static function extractClassMethod(callable $callback): array
    {
        if (is_array($callback)) {
            [$class_name, $method_name] = $callback;

            if (is_string($class_name)) {
                return [$class_name, $method_name];
            }
        } elseif (is_string($callback)) {
            [$class_name, $method_name] = explode('::', $callback, 2);

            return [$class_name, $method_name];
        }

        throw new LogicException('$callback is not expected callable format.');
    }
}
