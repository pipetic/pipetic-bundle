<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Utility;

use ByTIC\PackageBase\Utility\ModelFinder;
use Pipetic\Bundle\Droplets\Models\Droplets;
use Pipetic\Bundle\PipeticServiceProvider;
use Nip\Records\RecordManager;

/**
 * Class PipeticModels.
 */
class PipeticModels extends ModelFinder
{
    public const DROPLETS = 'droplets';

    public static function droplets(): Droplets|RecordManager
    {
        return static::getModels(self::DROPLETS, Droplets::class);
    }

    public static function dropletsClass(): string
    {
        return static::getConfigVar('models.' . self::DROPLETS, Droplets::class);
    }

    protected static function packageName(): string
    {
        return PipeticServiceProvider::NAME;
    }
}
