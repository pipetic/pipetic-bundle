<?php

use Pipetic\Bundle\Droplets\Models\Droplets;
use Pipetic\Bundle\Utility\PipeticModels;

return [

    'models' => array(
        PipeticModels::DROPLETS => Droplets::class,
    ),
    'tables' => [
        PipeticModels::DROPLETS => Droplets::TABLE,
    ],
    'database' => [
        'connection' => 'default',
        'migrations' => true,
    ],
];
