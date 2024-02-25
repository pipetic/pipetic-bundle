<?php

namespace Pipetic\Bundle\Droplets\Models;

use ByTIC\DataObjects\Casts\Metadata\AsMetadataObject;
use ByTIC\DataObjects\Casts\Metadata\Metadata;
use Pipetic\Bundle\Base\Models\Behaviours\HasId\RecordHasId;
use Pipetic\Bundle\Base\Models\Behaviours\Timestampable\TimestampableTrait;
use Pipetic\Bundle\PipeSubject\ModelsRelated\HasSubject\HasSubjectRecordTrait;

/**
 * Trait DropTrait
 * @property string|Metadata $metadata
 */
trait DropletTrait
{
    use RecordHasId;
    use HasSubjectRecordTrait;
    use Behaviours\HasStatus\HasStatusRecordTrait;
    use TimestampableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->addCast('metadata', AsMetadataObject::class . ':json');
    }
}
