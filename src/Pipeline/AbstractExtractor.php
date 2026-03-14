<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline;

use Pipetic\Bundle\Pipeline\Contracts\ExtractorInterface;

/**
 * Convenience base class for extractors.
 *
 * Concrete extractors should override {@see doExtract()} to yield records.
 */
abstract class AbstractExtractor implements ExtractorInterface
{
    public function extract(): iterable
    {
        yield from $this->doExtract();
    }

    /**
     * @return iterable<mixed>
     */
    abstract protected function doExtract(): iterable;
}
