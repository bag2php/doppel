<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Bag2\Doppel\Alter;
use Bag2\Doppel\Alter\AlterFactory;
use LogicException;

/**
 * A TestDouble class
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class TestDouble
{
    /** @var Alter */
    private $alter;

    /** @var AlterFactory */
    private $alter_factory;

    /** @var ?array{line:int, file:string} */
    private $backtrace;

    /** @var ?string */
    private $class_name;

    /** @var bool */
    private $disable_spy;

    /** @var string */
    private $method_name;

    /** @var array<int,array<int,mixed>> */
    private $received_args = [];

    /**
     * @param array{alter_factory?:AlterFactory, backtrace?: array{line:int, file:string}, disable_spy?:bool} $options
     */
    final private function __construct(?string $class_name, string $method_name, Replacer $replacer, array $options)
    {
        $this->class_name = $class_name;
        $this->method_name = $method_name;
        $this->alter_factory = $options['alter_factory'] ?? new Alter\DefaultFactory;
        $this->backtrace = $options['backtrace'] ?? null;
        $this->disable_spy = $options['disable_spy'] ?? false;

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
        $this->will($this->alter_factory->fromFixedReturnValue($value));

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
        if (!$this->disable_spy) {
            $this->received_args[] = $args;
        }

        return $this->alter->invoke($args);
    }

    /**
     * Defines the behavior of a function/method
     *
     * @param Alter $alter
     * @return $this
     */
    public function will($alter)
    {
        if (isset($this->alter)) {
            throw new LogicException('Already set Alter.  will() cannot be set multiple times.');
        }

        $this->alter = $alter;

        return $this;
    }
}
