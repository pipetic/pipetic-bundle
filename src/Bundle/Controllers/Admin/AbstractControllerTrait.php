<?php

declare(strict_types=1);

namespace Pipetic\Bundle\Bundle\Controllers\Admin;

use Nip\Controllers\Response\ResponsePayload;
use Pipetic\Bundle\Bundle\Library\View\ViewUtility;
use Nip\View\View;

/**
 * @method ResponsePayload payload()
 */
trait AbstractControllerTrait
{
    use \Pipetic\Bundle\Bundle\Controllers\Base\AbstractControllerTrait;

    protected function registerViewPathsPipetic(View $view): void
    {
        ViewUtility::registerViewPaths($view, 'admin');
    }
}
