<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline;

use Pipetic\Bundle\Pipeline\Contracts\LoaderInterface;

/**
 * Convenience base class for loaders.
 *
 * Concrete loaders should override {@see doLoad()}.
 */
abstract class AbstractLoader implements LoaderInterface
{
    public function load(mixed $record): void
    {
        $this->doLoad($record);
    }

    abstract protected function doLoad(mixed $record): void;
}
