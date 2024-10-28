<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Utility;

use ByTIC\PackageBase\Utility\ModelFinder;
use Pipetic\Bundle\DataNodes\Models\DataNodes;
use Pipetic\Bundle\Droplets\Models\Droplets;
use Pipetic\Bundle\PipeticServiceProvider;
use Nip\Records\RecordManager;

/**
 * Class PipeticModels.
 */
class PipeticModels extends ModelFinder
{
    public const DROPLETS = 'droplets';

    public const DATA_NODES = 'data_nodes';

    public static function droplets(): Droplets|RecordManager
    {
        return static::getModels(self::DROPLETS, Droplets::class);
    }

    public static function dropletsClass(): string
    {
        return static::getConfigVar('models.' . self::DROPLETS, Droplets::class);
    }

    public static function dataNodes(): RecordManager
    {
        return static::getModels(self::DATA_NODES, DataNodes::class);
    }

    public static function dataNodesClass(): string
    {
        return static::getConfigVar('models.' . self::DATA_NODES, DataNodes::class);
    }

    protected static function packageName(): string
    {
        return PipeticServiceProvider::NAME;
    }
}
