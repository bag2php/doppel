<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use ArrayAccess;
use Bag2\Doppel\Util\CallableHelper;
use Generator;
use LogicException;
use OutOfBoundsException;
use function debug_backtrace;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

/**
 * Manager and Factory class of TestDouble
 *
 * @implements ArrayAccess<string,MethodCallDoppel>
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class Manager implements ArrayAccess
{
    use CallableHelper;

    /** @var bool */
    private $finalized = false;

    /** @var Replacer */
    private $replacer;

    /** @var array<string,array<string,MethodCallDoppel>> */
    private $doppels = [];

    final public function __construct(Replacer $replacer)
    {
        $this->replacer = $replacer;
    }

    public function __destruct()
    {
        $this->finalized or $this->finalize();
    }

    /**
     * Create a test-double
     *
     * @param array{backtrace?: array{line:int, file:string}, enabled_record?:bool} $options
     */
    public function add(callable $method, array $options = []): MethodCallDoppel
    {
        if (!isset($options['backtrace'])) {
            /** @var array{line:int, file:string} */
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
            $options['backtrace'] = $backtrace;
        }

        [$class_name, $method_name] = $class_method = static::extractClassMethod($method);
        $test_double = MethodCallDoppel::fromClassMethod($class_method, $this->replacer, $options);

        if (isset($this->doppels[$class_name ?? ''][$method_name])) {
            $name = ($class_name === null) ? $method_name : "{$class_name}::{$method_name}";
            throw new LogicException("Already set \\{$name}(). Do not add it multiple times.");
        }

        $this->doppels[$class_name ?? ''][$method_name] = $test_double;

        return $test_double;
    }

    /**
     * @return Generator<MethodCallDoppel>
     */
    public function getMethodCallDoppels(): Generator
    {
        foreach ($this->doppels as $class => $methods) {
            foreach ($methods as $method => $test_double) {
                yield $test_double;
            }
        }
    }

    /**
     * Finalize test-doubles
     */
    public function finalize(): void
    {
        foreach ($this->getMethodCallDoppels() as $test_double) {
            $this->replacer->restore(...$test_double->asClassMethod());
        }

        $this->finalized = true;
    }

    /**
     * @param callable $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        [$class_name, $method_name] = static::extractClassMethod($offset);

        return isset($this->doppels[$class_name ?? ''][$method_name]);
    }

    /**
     * @param callable $offset
     * @return MethodCallDoppel
     */
    public function offsetGet($offset)
    {
        [$class_name, $method_name] = static::extractClassMethod($offset);

        return $this->doppels[$class_name ?? ''][$method_name];
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new OutOfBoundsException('Do not add MethodCallDoppel by assign/offsetSet().');
    }

    /**
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new OutOfBoundsException('Do not remove MethodCallDoppel by unset.');
    }
}
