<?php

declare(strict_types=1);

namespace Bag2\Doppel\Receptor;

use Bag2\Doppel\Receptor;
use Bag2\Doppel\UnexpectedMethodCallException;
use Exception;

/**
 * Method/Function Replacer implementation using uopz (User Operations for Zend)
 *
 * @see https://www.php.net/manual/book.uopz.php
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class CallCountReceptor implements Receptor
{
    /**
     * @var int
     * @phpstan-var 0|positive-int
     */
    private $called_count = 0;

    /**
     * @var ?int
     * @phpstan-var 0|positive-int
     */
    private $expected_called_count;

    /** @var string */
    private $func_name;

    /**
     * @phpstan-param 0|positive-int $expected_called_count
     */
    final public function __construct(string $func_name, int $expected_called_count)
    {
        assert($expected_called_count >= 0); // @phpstan-ignore-line
        $this->expected_called_count = $expected_called_count;
        $this->func_name = $func_name;
    }

    /**
     * Check arguments
     *
     * @param array<int,mixed> $arguments
     * @unused-param $param_name
     */
    public function call(array $arguments): bool
    {
        return $this->expected_called_count >= ++$this->called_count;
    }

    /**
     * Get Exception if receptor has violations
     *
     * @return UnexpectedMethodCallException
     */
    public function rejected(bool $finalized = false): ?Exception
    {
        $called_count = $this->called_count;

        if ($finalized) {
            if ($this->expected_called_count === $called_count) {
                return null;
            }
        } elseif ($this->expected_called_count >= $called_count) {
            return null;
        }

        assert($called_count > 0);

        return new UnexpectedMethodCallException(
            UnexpectedMethodCallException::generateMessageForCalledCount(
                $this->func_name,
                $this->expected_called_count,
                $called_count,
                $finalized
            )
        );
    }
}
