<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\TestCase;
use LogicException;

final class ReplacerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function raise_LogicException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('None of the Replacers are installed. Please install uopz or runkit7.');

        $_ = new ReplacerFactory([]);
    }
}
