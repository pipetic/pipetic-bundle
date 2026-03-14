<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline;

use Pipetic\Bundle\Pipeline\Contracts\TransformerInterface;

/**
 * Convenience base class for transformers.
 *
 * Concrete transformers should override {@see doTransform()}.
 * Return null from {@see doTransform()} to filter the record out.
 */
abstract class AbstractTransformer implements TransformerInterface
{
    public function transform(mixed $record): mixed
    {
        return $this->doTransform($record);
    }

    abstract protected function doTransform(mixed $record): mixed;
}
