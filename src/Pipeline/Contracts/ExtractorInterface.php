<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline\Contracts;

/**
 * Contract for the Extract step of an ETL pipeline.
 *
 * Implementations pull records from a data source and yield them
 * one at a time so large datasets are processed without loading
 * everything into memory.
 */
interface ExtractorInterface
{
    /**
     * Extract records from the source and return them as an iterable.
     *
     * Using a Generator (yield) is encouraged for memory efficiency.
     *
     * @return iterable<mixed>
     */
    public function extract(): iterable;
}
