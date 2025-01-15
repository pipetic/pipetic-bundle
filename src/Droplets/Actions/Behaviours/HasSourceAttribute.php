<?php

namespace Pipetic\Bundle\Droplets\Actions\Behaviours;

trait HasSourceAttribute
{

    public const ATTRIBUTE_SOURCE = 'source';

    public function getSource(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_SOURCE);
    }

    public function withSource(?string $source): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_SOURCE => $source]);
    }

}