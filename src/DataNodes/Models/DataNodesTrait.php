<?php

namespace Pipetic\Bundle\DataNodes\Models;

use Pipetic\Bundle\Base\Models\Behaviours\Timestampable\TimestampableManagerTrait;
use Pipetic\Bundle\Base\Models\Traits\HasDatabaseConnectionTrait;
use Pipetic\Bundle\PipeSubject\ModelsRelated\HasSubject\HasSubjectRepositoryTrait;
use Pipetic\Bundle\Utility\PackageConfig;
use Pipetic\Bundle\Utility\PipeticModels;

trait DataNodesTrait
{
    use TimestampableManagerTrait;
    use HasSubjectRepositoryTrait;
    use HasDatabaseConnectionTrait;

    protected function bootDataNodesTrait()
    {
//        static::updating(function ($event) {
//            /** @var Event $event */
//            UpdatePromotionCodes::for($event->getRecord());
//        });
    }

    protected function initRelationsPipetic(): void
    {
        $this->initRelationsPipeSubject();
    }


    public function generatePrimaryFK()
    {
        return 'datanode_id';
    }

    protected function generateTable(): string
    {
        return PackageConfig::tableName(PipeticModels::DATA_NODES, DataNodes::TABLE);
    }

    protected function generateMorphName(): string
    {
        return DataNodes::CONTROLLER;
    }
}
