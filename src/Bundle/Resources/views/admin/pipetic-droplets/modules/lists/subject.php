<?php
declare(strict_types=1);

use ByTIC\Icons\Icons;
use Pipetic\Bundle\Droplets\Models\Droplet;
use Pipetic\Bundle\Droplets\Statuses\Sent;

/** @var Droplet[] $items */
$items = $items ?? $this->pipetic_drops;
?>

<table class="table table-striped">
    <thead>
    <tr>
        <th><?= translator()->trans('destination'); ?></th>
        <th><?= translator()->trans('status'); ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item) { ?>
        <?php $subject = $item->getPipeSubject(); ?>
        <tr>
            <td>
                <?= $item->getDestinationName(); ?>
            </td>
            <td>
                <?= $item->getStatusObject()->getLabelHTML(); ?>
                <?php if (!$item->isInStatus([Sent::NAME])) { ?>
                    <span class="label">
                        <?= $item->tries; ?>
                    </span>
                <?php } ?>
            </td>
            <td>
                <?php if (!$item->isInStatus([Sent::NAME])) { ?>
                    <a href="<?= $item->compileURL('send'); ?>" class="btn btn-primary btn-xs">
                        Send
                    </a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>