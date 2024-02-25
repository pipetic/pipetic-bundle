<?php

namespace Pipetic\Bundle\Droplets\Statuses;

use ByTIC\Models\SmartProperties\Properties\Statuses\Generic;

abstract class AbstractStatus extends Generic
{
    public const NAME = null;

    public const DIRECTORY = __DIR__;
    protected function generateName(): string
    {
        return static::NAME;
    }
}