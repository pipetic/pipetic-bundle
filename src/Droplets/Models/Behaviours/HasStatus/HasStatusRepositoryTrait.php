<?php

namespace Pipetic\Bundle\Droplets\Models\Behaviours\HasStatus;

use ByTIC\Models\SmartProperties\RecordsTraits\HasStatus\RecordsTrait;
use Pipetic\Bundle\Droplets\Statuses\AbstractStatus;
use Pipetic\Bundle\Droplets\Statuses\Pending;

trait HasStatusRepositoryTrait
{
    use RecordsTrait;

    /**
     * @return string
     */
    public function getStatusItemsRootNamespace(): string
    {
        return 'Pipetic\Bundle\Droplets\Statuses\\';
    }

    /**
     * @return array
     */
    public function getStatusItemsDirectory(): array
    {
        return [AbstractStatus::DIRECTORY];
    }

    public function getDefaultStatus(): string
    {
        return Pending::NAME;
    }

}