<?php

namespace Pipetic\Bundle\PipeSubject\ModelsRelated\HasSubject;

trait HasSubjectRepositoryTrait
{
    protected function initRelationsPipeSubject(): void
    {
        $this->morphTo(
            'PipeSubject',
            ['morphPrefix' => 'subject', 'morphTypeField' => 'subject']
        );
    }
}
