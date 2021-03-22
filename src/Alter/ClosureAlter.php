<?php

declare(strict_types=1);

namespace Bag2\Doppel\Alter;

use Bag2\Doppel\Alter;
use Closure;
use function call_user_func_array;

class ClosureAlter implements Alter
{
    /** @var Closure */
    private $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param array<int,mixed> $args
     * @return mixed
     */
    public function invoke(array $args)
    {
        return call_user_func_array($this->closure, $args);
    }
}
