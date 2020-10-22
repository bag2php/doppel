<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Bag2\Doppel\Alter;

/**
 * This class returns values according to a simple rule
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 *
 * @phpstan-template T
 */
class ReturnValueAlter implements Alter
{
    public const TYPE_FIXED = 'fixed';

    /**
     * @var string
     * @phpstan-var self::TYPE_*
     */
    private $type;

    /**
     * @var array<mixed>
     * @phpstan-var array<T>
     */
    private $values;

    /**
     * @phpstan-param self::TYPE_* $type
     * @phpstan-param array<T> $values
     */
    public function __construct(string $type, array $values)
    {
        $this->type = $type;
        $this->values = $values;
    }

    /**
     * @param array<int,mixed> $args
     * @return mixed
     * @phpstan-return T
     * @phan-suppress PhanPluginAlwaysReturnMethod
     */
    public function invoke(array $args)
    {
        if ($this->type === self::TYPE_FIXED) {
            return $this->values[0];
        }
    }
}
