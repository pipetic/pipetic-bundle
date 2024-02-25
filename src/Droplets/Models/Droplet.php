<?php

namespace Pipetic\Bundle\Droplets\Models;

use Pipetic\Bundle\Base\Models\Traits\CommonRecordTrait;
use Nip\Records\Record;

/**
 * Class Droplet
 * @property string $destination
 * @package Pipetic\Bundle\Drops\Models
 */
class Droplet extends Record
{
    use DropletTrait;
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
