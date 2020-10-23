<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Bag2\Doppel\TestCase;

final class ClosureAlterTest extends TestCase
{
    public function test(): void
    {
        $subject = new ClosureAlter(function (int $a, int $b): int {
            return $a + $b;
        });

        $this->assertSame(3, $subject->invoke([1, 2]));
    }
}
