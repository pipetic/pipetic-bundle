<?php

namespace Pipetic\Bundle\DataNodes\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordsTrait;
use Nip\Records\RecordManager;

/**
 * Class DataNodes
 * @package Pipetic\Bundle\DataNodes\Models
 */
class DataNodes extends RecordManager
{
    public const TABLE = 'pipetic_datanodes';
    public const CONTROLLER = 'pipetic-datanodes';

    use DataNodesTrait;
    use CommonRecordsTrait;

}
