<?php

namespace Pipetic\Bundle\Drops\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordsTrait;
use Nip\Records\RecordManager;

/**
 * Class Drops
 * @package Pipetic\Bundle\Drops\Models
 */
class Drops extends RecordManager
{
    public const TABLE = 'pipetic_drops';
    public const CONTROLLER = 'pipetic-drops';

    use DropsTrait;
    use CommonRecordsTrait;

}
