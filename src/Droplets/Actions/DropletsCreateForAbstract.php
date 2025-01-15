<?php

namespace Pipetic\Bundle\Droplets\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecord;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Bundle\Droplets\Statuses\Pending;

abstract class DropletsCreateForAbstract extends Action
{
    use FindRecord;
    use HasSubject;
    use Behaviours\HasRepository;
    use Behaviours\HasDestinationAttribute;
    use Behaviours\HasSourceAttribute;
    use Behaviours\HasRefAttribute;

    protected function findParams(): array
    {
        $params = [];
        $params['where'] = [];
        $uniqueFields = $this->findUniqueFields();
        $data = $this->orCreateData([]);
        foreach ($uniqueFields as $field) {
            $params['where'][] = [$field . ' = ?', $data[$field]];
        }
        return $params;
    }

    protected function findUniqueFields(): array
    {
        return ['subject', 'subject_id', 'destination'];
    }

    protected function orCreateData($data)
    {
        $data['subject'] = $data['subject'] ?? $this->getSubject()->getManager()->getMorphName();
        $data['subject_id'] = $data['subject_id'] ?? $this->getSubject()->id;
        $data['destination'] = $data['destination'] ?? $this->getDestination();
        $data['source'] = $data['source'] ?? $this->getSource();
        $data['status'] = $data['status'] ?? Pending::NAME;
        $data['ref'] = $data['ref'] ?? $this->getRef();
        return $data;
    }
}
