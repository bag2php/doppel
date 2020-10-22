<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Bag2\Doppel\Util\CallableHelper;
use function debug_backtrace;
use const DEBUG_BACKTRACE_IGNORE_ARGS;
use Generator;
use LogicException;

/**
 * Manager and Factory class of TestDouble
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class Manager
{
    use CallableHelper;

    /** @var bool */
    private $finalized = false;

    /** @var Replacer */
    private $replacer;

    /** @var array<string,array<string,TestDouble>> */
    private $test_doubles = [];

    public function __construct(Replacer $replacer)
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
     * @param array{backtrace?: array{line:int, file:string}, enabled_spy?:bool} $options
     */
    public function add(callable $method, array $options = []): TestDouble
    {
        if (!isset($options['backtrace'])) {
            /** @var array{line:int, file:string} */
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
            $options['backtrace'] = $backtrace;
        }

        [$class_name, $method_name] = $class_method = static::extractClassMethod($method);
        $test_double = TestDouble::fromClassMethod($class_method, $this->replacer, $options);

        if (isset($this->test_doubles[$class_name ?? ''][$method_name])) {
            $name = ($class_name === null) ? $method_name : "{$class_name}::{$method_name}";
            throw new LogicException("Already set \\{$name}(). Do not add it multiple times.");
        }

        $this->test_doubles[$class_name ?? ''][$method_name] = $test_double;

        return $test_double;
    }

    /**
     * @return Generator<TestDouble>
     */
    public function getTestDoubles(): Generator
    {
        foreach ($this->test_doubles as $class => $methods) {
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
        foreach ($this->getTestDoubles() as $test_double) {
            $this->replacer->restore(...$test_double->asClassMethod());
        }

        $this->finalized = true;
    }
}
