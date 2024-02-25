<?php

namespace Pipetic\Bundle\Drops\DropStatuses;

use ByTIC\Models\SmartProperties\Properties\Statuses\Generic;

abstract class AbstractStatus extends Generic
{
    public const NAME = null;

    protected function generateName(): string
    {
        return static::NAME;
    }
}