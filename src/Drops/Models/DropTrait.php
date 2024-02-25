<?php

namespace Pipetic\Bundle\Drops\Models;

use ByTIC\DataObjects\Casts\Metadata\AsMetadataObject;
use ByTIC\DataObjects\Casts\Metadata\Metadata;
use Pipetic\Bundle\Base\Models\Behaviours\HasId\RecordHasId;
use Pipetic\Bundle\Base\Models\Behaviours\Timestampable\TimestampableTrait;

/**
 * Trait DropTrait
 * @property string|Metadata $metadata
 */
trait DropTrait
{
    use RecordHasId;
    use TimestampableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->addCast('metadata', AsMetadataObject::class . ':json');
    }
}
