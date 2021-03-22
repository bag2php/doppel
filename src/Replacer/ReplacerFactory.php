<?php

declare(strict_types=1);

namespace Bag2\Doppel\Replacer;

use Bag2\Doppel\Replacer;
use LogicException;
use function array_pop;
use function implode;

/**
 * A factory class of function replacer
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
final class ReplacerFactory
{
    /** @var class-string<Replacer> */
    private $class;

    /**
     * @param array<class-string<Replacer>> $replacer_candidates
     */
    public function __construct(array $replacer_candidates)
    {
        $this->setReplacer($replacer_candidates);
    }

    public function create(): Replacer
    {
        return new $this->class();
    }

    /**
     * @param array<class-string<Replacer>> $replacer_candidates
     */
    private function setReplacer(array $replacer_candidates): void
    {
        foreach ($replacer_candidates as $class) {
            if ($class::enabled()) {
                $this->class = $class;

                return;
            }
        }

        $last_replacer = array_pop($replacer_candidates);
        $message_replacers = $replacer_candidates
            ? implode(', ', $replacer_candidates) . "or {$last_replacer}"
            : $last_replacer ?? 'uopz or runkit7';

        throw new LogicException("None of the Replacers are installed. Please install {$message_replacers}.");
    }
}
