<?php
declare(strict_types=1);

use ByTIC\AdminBase\Widgets\Cards\Card;
use ByTIC\Icons\Icons;
use Pipetic\Bundle\Utility\PipeticModels;

$item = $item ?? $this->item;
$billingStatusesRepository = $billingStatusesRepository ?? PipeticModels::droplets();

$card = Card::make()
    ->withTitle($billingStatusesRepository->getLabel('title'))
    ->withIcon(Icons::list_ul())
    ->withTheme('inverse')
    ->wrapBody(false)
    ->withView($this)
    ->withViewContent(
        '/pipetic-droplets/modules/lists/subject',
        []
    );
?>
<?= $card; ?>
