<?php

namespace Pipetic\Bundle\Droplets\Models;

use Pipetic\Bundle\Base\Models\Behaviours\Timestampable\TimestampableManagerTrait;
use Pipetic\Bundle\Base\Models\Traits\HasDatabaseConnectionTrait;
use Pipetic\Bundle\PipeSubject\ModelsRelated\HasSubject\HasSubjectRepositoryTrait;
use Pipetic\Bundle\Utility\PackageConfig;
use Pipetic\Bundle\Utility\PipeticModels;

trait DropletsTrait
{
    use TimestampableManagerTrait;
    use HasSubjectRepositoryTrait;
    use Behaviours\HasStatus\HasStatusRepositoryTrait;
    use HasDatabaseConnectionTrait;

    protected function bootDropletsTrait()
    {
//        static::updating(function ($event) {
//            /** @var Event $event */
//            UpdatePromotionCodes::for($event->getRecord());
//        });
    }

    protected function initRelationsPipetic(): void
    {
    }


    public function generatePrimaryFK()
    {
        return 'droplet_id';
    }

    protected function generateTable(): string
    {
        return PackageConfig::tableName(PipeticModels::DROPLETS, Droplets::TABLE);
    }

    protected function generateMorphName(): string
    {
        return Droplets::CONTROLLER;
    }
}
