<?php

namespace Pipetic\Bundle\Drops\Models;

use Pipetic\Bundle\Base\Models\Behaviours\Timestampable\TimestampableManagerTrait;
use Pipetic\Bundle\Base\Models\Traits\HasDatabaseConnectionTrait;
use Pipetic\Bundle\Utility\PackageConfig;
use Pipetic\Bundle\Utility\PipeticModels;

trait DropsTrait
{
    use TimestampableManagerTrait;
    use HasDatabaseConnectionTrait;

    protected function bootDropsTrait()
    {
//        static::updating(function ($event) {
//            /** @var Event $event */
//            UpdatePromotionCodes::for($event->getRecord());
//        });
    }

    protected function initRelationsBilling(): void
    {
    }


    public function generatePrimaryFK()
    {
        return 'drop_id';
    }

    protected function generateTable(): string
    {
        return PackageConfig::tableName(PipeticModels::DROPS, Drops::TABLE);
    }

    protected function generateMorphName(): string
    {
        return Drops::CONTROLLER;
    }
}
