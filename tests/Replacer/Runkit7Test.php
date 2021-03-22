<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\TestCase;
use Bag2\Doppel\Util\CallableHelper;

final class Runkit7Test extends TestCase
{
    use CallableHelper;

    /** @var Runkit7 */
    private $subject;

    public function setUp(): void
    {
        if (!Runkit7::enabled()) {
            $this->markTestSkipped('Runkit7 is not available.');
        }

        $this->subject = new Runkit7();
    }

    /**
     * @test
     */
    public function method_register_and_restore(): void
    {
        $stashed_name = $this->stashMethodName(__CLASS__, 'target');

        $prefix = 'Replaced';
        $implementation = function (string $string) use ($prefix) {
            return "{$prefix}: {$string}";
        };

        $this->subject->register(__CLASS__, 'target', $implementation);
        $this->assertSame('Replaced: String', self::target('String'));
        $this->assertTrue(method_exists(__CLASS__, $stashed_name));

        $this->subject->restore(__CLASS__, 'target');
        $this->assertSame('Not replaced: String', self::target('String'));
        $this->assertFalse(method_exists(__CLASS__, $stashed_name));

        $this->subject->register(__CLASS__, 'target', $implementation);
        $this->assertSame('Replaced: String', self::target('String'));
        $this->assertTrue(method_exists(__CLASS__, $stashed_name));

        $this->subject->restore(__CLASS__, 'target');
        $this->assertSame('Not replaced: String', self::target('String'));
        $this->assertFalse(method_exists(__CLASS__, $stashed_name));
    }

    public static function target(string $string): string
    {
        return "Not replaced: {$string}";
    }
}
