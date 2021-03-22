<?php

declare(strict_types=1);

namespace Bag2\Doppel\Util;

use Bag2\Doppel\TestCase;

final class NumberTest extends TestCase
{
    use NumberHelper;

    /**
     * @dataProvider stashMethodNameProvider
     * @phpstan-param positive-int $input
     */
    public function test_stashMethodName(string $expected, int $input): void
    {
        $this->assertSame($expected, self::ordinal($input));
    }

    /**
     * @return array<array{0:string, 1:int}>
     * @phpstan-return array<array{0:string, 1:positive-int}>
     */
    public function stashMethodNameProvider(): array
    {
        $prefix = CallableHelper::class;

        return [
            ['1st', 1],
            ['2nd', 2],
            ['3rd', 3],
            ['4th', 4],
            ['5th', 5],
            ['6th', 6],
            ['7th', 7],
            ['8th', 8],
            ['9th', 9],
            ['10th', 10],
            ['11th', 11],
            ['12th', 12],
            ['13th', 13],
            ['14th', 14],
            ['15th', 15],
            ['16th', 16],
            ['17th', 17],
            ['18th', 18],
            ['19th', 19],
            ['20th', 20],
            ['21st', 21],
            ['22nd', 22],
            ['23rd', 23],
            ['24th', 24],
            ['25th', 25],
            ['26th', 26],
            ['27th', 27],
            ['28th', 28],
            ['29th', 29],
            ['30th', 30],
            ['31st', 31],
            ['32nd', 32],
            ['33rd', 33],
            ['34th', 34],
            ['35th', 35],
            ['36th', 36],
            ['37th', 37],
            ['38th', 38],
            ['39th', 39],
            ['40th', 40],
            ['99th', 99],
            ['100th', 100],
            ['101st', 101],
            ['110th', 110],
            ['111th', 111],
            ['112th', 112],
            ['113th', 113],
            ['114th', 114],
        ];
    }
}
