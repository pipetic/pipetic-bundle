<?php

namespace Pipetic\Bundle\Droplets\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecord;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Bundle\Droplets\Statuses\Pending;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasRepository;

class DropletsCreateForSubject extends Action
{
    use FindRecord;
    use HasSubject;
    use HasRepository;

    public const ATTRIBUTE_DESTINATION = 'destination';

    public function getDestination(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_DESTINATION);
    }

    public function withDestination(?string $destination): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_DESTINATION => $destination]);
    }

    protected function findParams(): array
    {
        return [
            'where' => [
                ['subject = ?', $this->getSubject()->getManager()->getMorphName()],
                ['subject_id = ?', $this->getSubject()->id],
                ['destination = ?', $this->getDestination()],
            ]
        ];
    }

    protected function orCreateData($data)
    {
        $data['subject'] = $data['subject'] ?? $this->getSubject()->getManager()->getMorphName();
        $data['subject_id'] = $data['subject_id'] ?? $this->getSubject()->id;
        $data['destination'] = $data['destination'] ?? $this->getDestination();
        $data['status'] = $data['status'] ?? Pending::NAME;
        return $data;
    }
}
