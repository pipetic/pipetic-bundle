<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline\Contracts;

/**
 * Contract for the Transform step of an ETL pipeline.
 *
 * A transformer receives a single record from the extractor (or from
 * a previous transformer) and returns the transformed record.  Returning
 * null signals that the record should be skipped / filtered out.
 */
interface TransformerInterface
{
    /**
     * Transform a single record.
     *
     * @param mixed $record The record to transform.
     *
     * @return mixed The transformed record, or null to skip it.
     */
    public function transform(mixed $record): mixed;
}
