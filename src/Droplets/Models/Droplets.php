<?php

namespace Pipetic\Bundle\Droplets\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordsTrait;
use Nip\Records\RecordManager;

/**
 * Class Droplets
 * @package Pipetic\Bundle\Drops\Models
 */
class Droplets extends RecordManager
{
    public const TABLE = 'pipetic_droplets';
    public const CONTROLLER = 'pipetic-droplets';

    use DropletsTrait;
    use CommonRecordsTrait;

}
