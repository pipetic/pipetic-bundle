<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline\Contracts;

/**
 * Contract for the Load step of an ETL pipeline.
 *
 * Implementations write a single (already-transformed) record to
 * the destination system.
 */
interface LoaderInterface
{
    /**
     * Load a single record into the destination.
     *
     * @param mixed $record The record to load.
     */
    public function load(mixed $record): void;
}
