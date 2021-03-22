<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Bag2\Doppel\TestCase;

final class DefaultFactoryTest extends TestCase
{
    /** @var DefaultFactory */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new DefaultFactory();
    }

    public function test_createFromClosure(): void
    {
        $actual = $this->subject->createFromClosure(function (int $a, int $b): int {
            return $a - $b;
        });

        $this->assertInstanceOf(ClosureAlter::class, $actual);
        $this->assertSame(10, $actual->invoke([20, 10]));
    }

    public function test_createFromFixedReturnValue(): void
    {
        $actual = $this->subject->createFromFixedReturnValue(PHP_INT_MAX);

        $this->assertInstanceOf(ReturnValueAlter::class, $actual);
        $this->assertSame(PHP_INT_MAX, $actual->invoke([20, 10]));
    }
}
