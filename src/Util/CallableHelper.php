<?php

declare(strict_types=1);

namespace Bag2\Doppel\Util;

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
 *
 * @psalm-internal Bag2\Doppel
 */
trait CallableHelper
{
    /**
     * Extract class-name and method-name from callable value.
     *
     * @return array{0:?string,1:string}
     */
    private static function extractClassMethod(callable $callback): array
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

    /**
     * Generate a new function name for method stashing that includes backslash(\\) and null(\0)
     */
    private static function stashMethodName(string $class_name, string $func_name): string
    {
        return __TRAIT__ . "\0{$class_name}\0{$func_name}\0";
    }
}
