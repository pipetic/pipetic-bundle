<?php

namespace Pipetic\Bundle\Droplets\Actions\Behaviours;

trait HasDestinationAttribute
{
    public const ATTRIBUTE_DESTINATION = 'destination';
    public function getDestination(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_DESTINATION);
    }

    public function withDestination(?string $destination): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_DESTINATION => $destination]);
    }
}