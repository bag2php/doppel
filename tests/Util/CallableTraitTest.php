<?php

declare(strict_types=1);

namespace Bag2\StaticDouble\Util;

use Bag2\StaticDouble\TestCase;
use Bag2\StaticDouble\Util\CallableTrait;
use LogicException;

final class Runkit7Test extends TestCase
{
    use CallableTrait;

    /**
     * @dataProvider stashMethodNameProvider
     */
    public function test_stashMethodName(string $expected, string $class_name, string $method_name): void
    {
        $this->assertSame($expected, $this->stashMethodName($class_name, $method_name));
    }

    /**
     * @return array<array{0:string, 1:string, 2:string}>
     */
    public function stashMethodNameProvider(): array
    {
        $prefix = CallableTrait::class;

        return [
            ["{$prefix}\0Book\0all\0", 'Book', 'all'],
            ["{$prefix}\0\0func\0", '', 'func'],
        ];
    }
}
