<?php

use Pipetic\Bundle\Droplets\Models\Droplets;
use Pipetic\Bundle\Salesforce\Auth\OAuthConfig;
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

    /*
    |--------------------------------------------------------------------------
    | Salesforce OAuth2 Configuration
    |--------------------------------------------------------------------------
    |
    | Settings required to connect to a Salesforce organisation via OAuth2.
    | In multi-tenant deployments each tenant may override these values at
    | runtime by constructing their own OAuthConfig instance.
    |
    | login_url: Use OAuthConfig::LOGIN_URL for production orgs or
    |            OAuthConfig::SANDBOX_URL for sandboxes.
    |
    */
    'salesforce' => [
        'client_id'     => env('SALESFORCE_CLIENT_ID', ''),
        'client_secret' => env('SALESFORCE_CLIENT_SECRET', ''),
        'redirect_uri'  => env('SALESFORCE_REDIRECT_URI', ''),
        'login_url'     => env('SALESFORCE_LOGIN_URL', OAuthConfig::LOGIN_URL),
        'api_version'   => env('SALESFORCE_API_VERSION', 'v59.0'),
    ],
];
