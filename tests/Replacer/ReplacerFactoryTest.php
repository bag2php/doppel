<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\Replacer;
use Bag2\Doppel\TestCase;
use LogicException;

final class ReplacerFactoryTest extends TestCase
{
    public function test(): void
    {
        $subject = new ReplacerFactory([EnabledDummyReplacer::class]);
        $actual = $subject->create();

        $this->assertInstanceOf(EnabledDummyReplacer::class, $actual);
    }

    /**
     * @test
     * @dataProvider raiseCandidatesProvider
     * @phpstan-param array<class-string<Replacer>> $candidates
     */
    public function raise_LogicException(string $expected_message, array $candidates): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($expected_message);

        $_ = new ReplacerFactory($candidates);
    }

    /**
     * @phpstan-return array<string,array{0:string,1:array<class-string<Replacer>>}>
     */
    public function raiseCandidatesProvider(): array
    {
        return [
            'Empty candidates' => [
                'None of the Replacers are installed. Please install uopz or runkit7.',
                []
            ],
            'Has only disabled replacer' => [
                'None of the Replacers are installed. Please install Bag2\Doppel\Replacer\DisabledDummyReplacer.',
                [DisabledDummyReplacer::class]
            ],
        ];
    }
}
