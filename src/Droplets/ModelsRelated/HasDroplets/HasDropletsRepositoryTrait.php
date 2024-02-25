<?php

namespace Pipetic\Bundle\Droplets\ModelsRelated\HasDroplets;

use Pipetic\Bundle\Utility\PipeticModels;

trait HasDropletsRepositoryTrait
{

    protected function initRelationPipeticDroplets(): void
    {
        $this->morphMany(
            HasDropletsRepository::RELATION_BILLING_STATUS,
            [
                'class' => PipeticModels::droplets(),
                'morphPrefix' => 'subject',
                'morphTypeField' => 'subject',
            ]
        );
    }
}
