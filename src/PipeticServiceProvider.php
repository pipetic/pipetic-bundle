<?php

declare(strict_types=1);

namespace Pipetic\Bundle;

use ByTIC\PackageBase\BaseBootableServiceProvider;
use Pipetic\Bundle\Utility\PackageConfig;

/**
 * Class PipeticServiceProvider.
 */
class PipeticServiceProvider extends BaseBootableServiceProvider
{
    public const NAME = 'pipetic';

    public function migrations(): ?string
    {
        if (PackageConfig::shouldRunMigrations()) {
            return \dirname(__DIR__) . '/migrations/';
        }

        return null;
    }

    protected function translationsPath(): string
    {
        return __DIR__ . '/Bundle/Resources/lang/';
    }
}
