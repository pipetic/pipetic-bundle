<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Bundle\Controllers\Admin;

use Pipetic\Bundle\Droplets\Actions\DropletsCreateForSubject;

trait PipeSubjectControllerTrait
{
    use AbstractControllerTrait;

    protected function fillPayloadWithDrops($subject, $destination = null): void
    {
        $destination = !is_array($destination) ? [$destination] : $destination;

        $drops = [];
        foreach ($destination as $dest) {
            $drops[$dest] = DropletsCreateForSubject::for($subject)
                ->withDestination($dest)
                ->orCreate()
                ->fetch();
        }
        $this->payload()->with(['pipetic_drops' => $drops]);
    }
}