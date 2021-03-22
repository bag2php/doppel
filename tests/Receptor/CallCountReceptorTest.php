<?php

declare(strict_types=1);

namespace Bag2\Doppel\Receptor;

use Bag2\Doppel\TestCase;
use Bag2\Doppel\UnexpectedMethodCallException;

final class CallCountReceptorTest extends TestCase
{
    public function test(): void
    {
        $subject = new CallCountReceptor('func', 3);

        $this->assertTrue($subject->call([]));
        $this->assertTrue($subject->call([]));

        $this->assertNull($subject->rejected());

        $e1 = $subject->rejected(true);
        $this->assertInstanceOf(UnexpectedMethodCallException::class, $e1);
        $this->assertSame(
            'func() is expected to be called 3 times, but it was called 2 times.',
            $e1->getMessage()
        );

        $this->assertTrue($subject->call([]));

        $this->assertNull($subject->rejected());
        $this->assertNull($subject->rejected(true));

        $this->assertFalse($subject->call([]));

        $e2 = $subject->rejected(false);

        $this->assertInstanceOf(UnexpectedMethodCallException::class, $e2);
        $this->assertSame(
            'func() is expected to be called 3 times, but it was called the 4th time.',
            $e2->getMessage()
        );

        $e3 = $subject->rejected(true);
        assert($e3 !== null); // @phpstan-ignore-line
        $this->assertInstanceOf(UnexpectedMethodCallException::class, $e3);
        $this->assertSame(
            'func() is expected to be called 3 times, but it was called 4 times.',
            $e3->getMessage()
        );
    }

    public function test_expect_never_called(): void
    {
        $subject = new CallCountReceptor('func', 0);

        $this->assertNull($subject->rejected());
        $this->assertNull($subject->rejected(true));

        $this->assertFalse($subject->call([]));

        $e1 = $subject->rejected(true);
        $this->assertInstanceOf(UnexpectedMethodCallException::class, $e1); // @phpstan-ignore-line
        $this->assertSame(
            'func() is expected to never be called, but it was 1 times called.',
            $e1->getMessage()
        );
    }
}
