<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Bag2\Doppel\Alter;
use Bag2\Doppel\Alter\AlterFactory;
use Closure;
use LogicException;

/**
 * Doppel of calling a method/function, not mock.
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class MethodCallDoppel
{
    /** @var Alter */
    private $alter;

    /** @var AlterFactory */
    private $alter_factory;

    /** @var ?array{line:int, file:string} */
    private $backtrace;

    /**
     * @var int
     * @phpstan-var 0|positive-int
     */
    private $called_count = 0;

    /** @var ?string */
    private $class_name;

    /** @var bool */
    private $enable_record;

    /** @var bool */
    private $enable_throw_on_runtime;

    /**
     * @var ?int
     * @phpstan-var null|0|positive-int
     */
    private $expected_called_count;

    /** @var string */
    private $method_name;

    /**
     * @var array<int,array<int,mixed>>
     * @psalm-var list<int,list<int,mixed>>
     */
    private $received_args = [];

    /**
     * @param array{alter_factory?:AlterFactory, backtrace?: array{line:int, file:string}, enable_record?:bool} $options
     */
    final private function __construct(?string $class_name, string $method_name, Replacer $replacer, array $options)
    {
        $this->class_name = $class_name;
        $this->method_name = $method_name;
        $this->alter_factory = $options['alter_factory'] ?? new Alter\DefaultFactory;
        $this->backtrace = $options['backtrace'] ?? null;
        $this->enable_throw_on_runtime = $options['enable_throw_on_runtime'] ?? true;
        $this->enable_record = $options['enable_record'] ?? true;

        $test_double = $this;

        /**
         * @param mixed ...$args
         * @return mixed
         */
        $implementation = static function (...$args) use ($test_double) {
            return $test_double->invokeImplementation($args);
        };
        $replacer->register($class_name, $method_name, $implementation);
    }

    /**
     * @param array{0:?string,1:string} $class_method
     * @param array{backtrace?: array{line:int, file:string}} $options
     * @return static
     */
    public static function fromClassMethod(array $class_method, Replacer $replacer, array $options)
    {
        [$class_name, $method_name] = $class_method;

        return new static($class_name, $method_name, $replacer, $options);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function andReturn($value)
    {
        $this->will($this->alter_factory->createFromFixedReturnValue($value));

        return $this;
    }

    /**
     * @return array{0:?string,1:string}
     */
    public function asClassMethod(): array
    {
        return [$this->class_name, $this->method_name];
    }

    /**
     * @param array<mixed> $args
     * @return mixed
     */
    public function invokeImplementation(array $args)
    {
        $this->called_count++;
        if ($this->enable_record) {
            $this->received_args[] = $args;
        }

        if (isset($this->expected_called_count) &&
            $this->enable_throw_on_runtime &&
            $this->expected_called_count < $this->called_count
        ) {
            $func_name = $this->class_name === null
                ? $this->method_name
                : "{$this->class_name}::{$this->method_name}";

            throw new UnexpectedMethodCallException(
                UnexpectedMethodCallException::generateMessageForCalledCount(
                    $func_name,
                    $this->expected_called_count,
                    $this->called_count
                )
            );
        }

        return $this->alter->invoke($args);
    }

    /**
     * @return $this
     */
    public function never()
    {
        return $this->times(0);
    }

    /**
     * @return $this
     */
    public function once()
    {
        return $this->times(1);
    }

    /**
     * @phpstan-param 0|positive-int $n
     * @return $this
     */
    public function times(int $n)
    {
        /* @phpstan-ignore-next-line */
        assert($n >= 0);
        $this->expected_called_count = $n;

        return $this;
    }

    /**
     * @return $this
     */
    public function twice()
    {
        return $this->times(2);
    }

    /**
     * Defines the behavior of a function/method
     *
     * @param Alter|Closure $alter
     * @return $this
     */
    public function will($alter)
    {
        if (isset($this->alter)) {
            throw new LogicException('Already set Alter.  will() cannot be set multiple times.');
        }

        if ($alter instanceof Closure) {
            $alter = $this->alter_factory->createFromClosure($alter);
        }

        $this->alter = $alter;

        return $this;
    }
}
