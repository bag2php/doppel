<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Exception;

/**
 * The interface of method/function arguments receptors
 *
 * A class called "receptor" passed arguments when method is called and checks it matches expected.
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
interface Receptor
{
    /**
     * Check arguments
     *
     * @param array<int,mixed> $arguments
     */
    public function call(array $arguments): bool;

    /**
     * Get Exception if receptor has violations
     */
    public function rejected(bool $finalized = false): ?Exception;
}
