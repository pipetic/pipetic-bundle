<?php

use Pipetic\Bundle\Utility\PipeticModels;

return [

    'models' => array(
        PipeticModels::DROPS => Invoices::class,
    ),
    'tables' => [
        PipeticModels::DROPS => Invoices::TABLE,
    ],
    'database' => [
        'connection' => 'default',
        'migrations' => true,
    ],
];
