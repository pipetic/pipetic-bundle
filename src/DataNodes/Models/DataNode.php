<?php

namespace Pipetic\Bundle\DataNodes\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordTrait;
use Nip\Records\Record;

/**
 * Class DataNode
 * @property string $destination
 * @package Pipetic\Bundle\DataNodes\Models
 */
class DataNode extends Record
{
    use DataNodeTrait;
    use CommonRecordTrait;


    public function getDestinationName()
    {
        return $this->destination;
    }

    public function getRegistry()
    {
        // TODO: Implement getRegistry() method.
    }
}
