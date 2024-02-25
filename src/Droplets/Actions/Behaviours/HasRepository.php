<?php

namespace Pipetic\Bundle\Droplets\Actions\Behaviours;

use Nip\Records\RecordManager;
use Pipetic\Bundle\Droplets\Models\Droplets;
use Pipetic\Bundle\Utility\PipeticModels;

trait HasRepository
{

    protected function generateRepository(): Droplets|RecordManager
    {
        return PipeticModels::droplets();
    }
}