<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Pipeline;

use Pipetic\Bundle\Pipeline\Contracts\ExtractorInterface;
use Pipetic\Bundle\Pipeline\Contracts\LoaderInterface;
use Pipetic\Bundle\Pipeline\Contracts\TransformerInterface;

/**
 * ETL Pipeline orchestrator.
 *
 * Inspired by wizacode/php-etl, jwhulette/pipes, and php-etl/pipeline.
 *
 * Usage:
 *
 *   (new Pipeline())
 *       ->extract(new MySourceExtractor())
 *       ->pipe(new MapFieldsTransformer($fieldMap))
 *       ->pipe(new ValidateRecordTransformer())
 *       ->load(new LocalDatabaseLoader($repository))
 *       ->run();
 */
class Pipeline
{
    private ?ExtractorInterface $extractor = null;

    /** @var TransformerInterface[] */
    private array $transformers = [];

    private ?LoaderInterface $loader = null;

    private int $processed = 0;

    private int $skipped = 0;

    /**
     * Set the extractor (source) for this pipeline.
     */
    public function extract(ExtractorInterface $extractor): static
    {
        $this->extractor = $extractor;

        return $this;
    }

    /**
     * Append a transformer to the pipeline.
     *
     * Transformers are executed in the order they are added.  A transformer
     * may return null to signal that the current record should be skipped.
     */
    public function pipe(TransformerInterface $transformer): static
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * Set the loader (destination) for this pipeline.
     */
    public function load(LoaderInterface $loader): static
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Execute the pipeline: Extract → Transform → Load.
     *
     * @throws \LogicException When extractor or loader has not been set.
     */
    public function run(): void
    {
        if ($this->extractor === null) {
            throw new \LogicException('A pipeline must have an extractor set before calling run().');
        }

        if ($this->loader === null) {
            throw new \LogicException('A pipeline must have a loader set before calling run().');
        }

        $this->processed = 0;
        $this->skipped = 0;

        foreach ($this->extractor->extract() as $record) {
            $record = $this->applyTransformers($record);

            if ($record === null) {
                $this->skipped++;
                continue;
            }

            $this->loader->load($record);
            $this->processed++;
        }
    }

    /**
     * Returns the number of records successfully loaded in the last run.
     */
    public function getProcessedCount(): int
    {
        return $this->processed;
    }

    /**
     * Returns the number of records skipped (filtered out) in the last run.
     */
    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    private function applyTransformers(mixed $record): mixed
    {
        foreach ($this->transformers as $transformer) {
            $record = $transformer->transform($record);

            if ($record === null) {
                return null;
            }
        }

        return $record;
    }
}
