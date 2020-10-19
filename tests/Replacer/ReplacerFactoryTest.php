<?php

declare(strict_types=1);

namespace Bag2\StaticDouble\Replacer;

use Bag2\StaticDouble\TestCase;
use LogicException;

final class ReplacerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function raise_LogicException()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('None of the Replacers are installed. Please install uopz or runkit7.');

        $_ = new ReplacerFactory([]);
    }
}
