<?php

namespace Pipetic\Bundle\Drops\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordTrait;
use Nip\Records\Record;

/**
 * Class Drop
 * @package Pipetic\Bundle\Drops\Models
 */
class Drop extends Record
{
    use DropTrait;
    use CommonRecordTrait;

    public const METADATA_EXTERNAL_LINK = 'external_link';

    public function getRegistry()
    {
        // TODO: Implement getRegistry() method.
    }


}
