<?php

namespace Pipetic\Bundle\Droplets\Actions\Behaviours;

trait HasRefAttribute
{

    public const ATTRIBUTE_REF = 'ref';

    public function getRef(): ?string
    {
        return $this->getAttribute(self::ATTRIBUTE_REF);
    }

    public function withRef(?string $ref): self
    {
        return $this->fillAttributes([self::ATTRIBUTE_REF => $ref]);
    }
}