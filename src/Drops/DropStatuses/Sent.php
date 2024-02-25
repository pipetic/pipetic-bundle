<?php

namespace Pipetic\Bundle\Drops\DropStatuses;

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