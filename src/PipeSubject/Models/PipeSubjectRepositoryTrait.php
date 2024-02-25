<?php

namespace Pipetic\Bundle\PipeSubject\Models;

use Pipetic\Bundle\Droplets\ModelsRelated\HasDroplets\HasDropletsRepositoryTrait;

trait PipeSubjectRepositoryTrait
{
    use HasDropletsRepositoryTrait;

    protected function initRelationsPipetic(): void
    {
        $this->initRelationPipeticDroplets();
    }
}