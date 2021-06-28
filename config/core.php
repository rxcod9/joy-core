<?php

return [

    /*
     * The config_key for core package.
     */
    'config_key' => env('CORE_CONFIG_KEY', 'joy-core'),

    /*
     * The route_prefix for core package.
     */
    'route_prefix' => env('CORE_ROUTE_PREFIX', 'joy-core'),

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify voyager controller settings
    |
    */

    'controllers' => [
        'namespace' => 'Joy\\Core\\Http\\Controllers',
    ],
];
