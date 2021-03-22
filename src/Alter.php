<?php

declare(strict_types=1);

namespace Bag2\Doppel;

/**
 * The interface that is an alternative to function/method implementation
 *
 * In formal English, "alter" is a verb and is not suitable as a class name.
 * However, in this context it is a noun defined as a proprietary term, meaning "alternative".
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
interface Alter
{
    /**
     * @param array<mixed> $args
     * @return mixed
     */
    public function invoke(array $args);
}
