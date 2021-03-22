<?php

declare(strict_types=1);

namespace Bag2\Doppel\Util;

use Bag2\Doppel\TestCase;

final class CallableHelperTest extends TestCase
{
    use CallableHelper;

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
        $prefix = CallableHelper::class;

        return [
            ["{$prefix}\0Book\0all\0", 'Book', 'all'],
            ["{$prefix}\0\0func\0", '', 'func'],
        ];
    }
}
