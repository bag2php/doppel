<?php

declare(strict_types=1);

namespace Bag2\Doppel;

use Bag2\Doppel\Replacer\ReplacerFactory;

/**
 * A factory class of Static TestDouble manager
 *
 * @author USAMI Kenta <tadsan@zonu.me>
 * @copyright 2020 Baguette HQ
 * @license https://www.mozilla.org/en-US/MPL/2.0/ MPL-2.0
 */
class Factory
{
    private const REPLACER_CANDIDATES = [
        Replacer\Runkit7::class,
        Replacer\Uopz::class,
    ];

    /** @var ReplacerFactory */
    private $replacer_factory;

    /**
     * @param array{replacer_factory?:ReplacerFactory} $options
     */
    public function __construct(array $options = [])
    {
        $this->replacer_factory = $options['replacer_factory'] ??
            new ReplacerFactory(static::REPLACER_CANDIDATES);
    }

    /**
     * Create new instance of Manager class
     *
     * If you want to extend Manager, pass YourCustomizedManager::class value.
     *
     * @phpstan-template T of Manager
     * @phpstan-param class-string<T> $manager_class
     * @phpstan-return T
     */
    public function create(string $manager_class = Manager::class): Manager
    {
        return new $manager_class(
            $this->replacer_factory->create()
        );
    }
}
