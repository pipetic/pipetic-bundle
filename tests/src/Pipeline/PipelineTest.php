<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Tests\Pipeline;

use PHPUnit\Framework\TestCase;
use Pipetic\Bundle\Pipeline\AbstractExtractor;
use Pipetic\Bundle\Pipeline\AbstractLoader;
use Pipetic\Bundle\Pipeline\AbstractTransformer;
use Pipetic\Bundle\Pipeline\Contracts\ExtractorInterface;
use Pipetic\Bundle\Pipeline\Contracts\LoaderInterface;
use Pipetic\Bundle\Pipeline\Contracts\TransformerInterface;
use Pipetic\Bundle\Pipeline\Pipeline;

class PipelineTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function makeExtractor(array $records): ExtractorInterface
    {
        return new class ($records) extends AbstractExtractor {
            public function __construct(private array $data)
            {
            }

            protected function doExtract(): iterable
            {
                yield from $this->data;
            }
        };
    }

    private function makeLoader(): AbstractLoader
    {
        return new class extends AbstractLoader {
            public array $loaded = [];

            protected function doLoad(mixed $record): void
            {
                $this->loaded[] = $record;
            }
        };
    }

    private function makeDoubleTransformer(): TransformerInterface
    {
        return new class extends AbstractTransformer {
            protected function doTransform(mixed $record): mixed
            {
                return $record * 2;
            }
        };
    }

    private function makeFilterTransformer(mixed $skipValue): TransformerInterface
    {
        return new class ($skipValue) extends AbstractTransformer {
            public function __construct(private mixed $skip)
            {
            }

            protected function doTransform(mixed $record): mixed
            {
                return $record === $this->skip ? null : $record;
            }
        };
    }

    // -----------------------------------------------------------------------
    // Tests
    // -----------------------------------------------------------------------

    public function testRunWithoutExtractorThrows(): void
    {
        $pipeline = (new Pipeline())->load($this->makeLoader());

        $this->expectException(\LogicException::class);
        $pipeline->run();
    }

    public function testRunWithoutLoaderThrows(): void
    {
        $pipeline = (new Pipeline())->extract($this->makeExtractor([1, 2, 3]));

        $this->expectException(\LogicException::class);
        $pipeline->run();
    }

    public function testSimpleExtractLoad(): void
    {
        $loader = $this->makeLoader();

        (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3]))
            ->load($loader)
            ->run();

        $this->assertSame([1, 2, 3], $loader->loaded);
    }

    public function testTransformIsApplied(): void
    {
        $loader = $this->makeLoader();

        (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3]))
            ->pipe($this->makeDoubleTransformer())
            ->load($loader)
            ->run();

        $this->assertSame([2, 4, 6], $loader->loaded);
    }

    public function testMultipleTransformers(): void
    {
        $loader = $this->makeLoader();

        (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3]))
            ->pipe($this->makeDoubleTransformer())   // 1→2, 2→4, 3→6
            ->pipe($this->makeDoubleTransformer())   // 2→4, 4→8, 6→12
            ->load($loader)
            ->run();

        $this->assertSame([4, 8, 12], $loader->loaded);
    }

    public function testFilterTransformerSkipsRecords(): void
    {
        $loader = $this->makeLoader();

        (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3]))
            ->pipe($this->makeFilterTransformer(2))
            ->load($loader)
            ->run();

        $this->assertSame([1, 3], $loader->loaded);
    }

    public function testProcessedAndSkippedCounts(): void
    {
        $loader   = $this->makeLoader();
        $pipeline = (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3, 4, 5]))
            ->pipe($this->makeFilterTransformer(3))
            ->load($loader);

        $pipeline->run();

        $this->assertSame(4, $pipeline->getProcessedCount());
        $this->assertSame(1, $pipeline->getSkippedCount());
    }

    public function testEmptyExtractorProducesNothingLoaded(): void
    {
        $loader = $this->makeLoader();

        (new Pipeline())
            ->extract($this->makeExtractor([]))
            ->load($loader)
            ->run();

        $this->assertSame([], $loader->loaded);
    }

    public function testCountersResetBetweenRuns(): void
    {
        $loader   = $this->makeLoader();
        $pipeline = (new Pipeline())
            ->extract($this->makeExtractor([1, 2, 3]))
            ->pipe($this->makeFilterTransformer(2))
            ->load($loader);

        $pipeline->run();
        $this->assertSame(2, $pipeline->getProcessedCount());

        $pipeline->run();
        // After second run counters reflect only that run.
        $this->assertSame(2, $pipeline->getProcessedCount());
        $this->assertSame(1, $pipeline->getSkippedCount());
    }
}
