<?php

namespace Pipetic\Bundle\Droplets\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecord;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Bundle\Droplets\Statuses\Pending;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasRepository;

class DropletsCreateForSubject extends DropletsCreateForAbstract
{
    protected function findParams(): array
    {
        $params = parent::findParams();
        $params['where'][] = ['destination = ?', $this->getDestination()];
        return $params;
    }
}
