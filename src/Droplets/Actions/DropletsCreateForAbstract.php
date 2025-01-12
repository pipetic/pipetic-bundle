<?php

namespace Pipetic\Bundle\Droplets\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecord;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Bundle\Droplets\Statuses\Pending;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasRepository;

abstract class DropletsCreateForAbstract extends Action
{
    use FindRecord;
    use HasSubject;
    use HasRepository;

    public const ATTRIBUTE_DESTINATION = 'destination';

    public const ATTRIBUTE_SOURCE = 'source';

    public const ATTRIBUTE_REF = 'ref';

    public function getDestination(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_DESTINATION);
    }

    public function withDestination(?string $destination): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_DESTINATION => $destination]);
    }

    public function getSource(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_SOURCE);
    }

    public function withSource(?string $source): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_SOURCE => $source]);
    }

    public function getRef(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_REF);
    }

    public function withRef(?string $ref): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_REF => $ref]);
    }

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
