<?php

namespace Pipetic\Bundle\Droplets\Statuses;

/**
 *
 */
class Sent extends AbstractStatus
{
    public const NAME = 'sent';

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritDoc
     */
    public function getColorClass()
    {
        return 'primary';
    }

}