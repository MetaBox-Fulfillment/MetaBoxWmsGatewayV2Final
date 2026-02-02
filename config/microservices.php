<?php
$is_prod = env('APP_ENV') === 'production';
return [

    'services' => [
        'inventory' => [
            'base_url' => $is_prod ? 'https://ms-inventory.metabox.hu/api' : 'https://dev-ms-inventory.metabox.hu/api',
            'timeout' => 10,
            'api_key' => $is_prod ? env('MS_INVENTORY_API_KEY') : env('DEV_MS_INVENTORY_API_KEY'),
            'api_key_header' => 'X-API-Key',
        ],
        'partner' => [
            'base_url' => $is_prod ? "https://ms-partners.metabox.hu/api" : "https://dev-ms-partners.metabox.hu/api",
            'timeout' => 10,
            'api_key' => $is_prod ? env('MS_PARTNER_API_KEY') : env('DEV_MS_PARTNER_API_KEY'),
            'api_key_header' => 'X-API-Key',
        ],
        'wse_engine' => [
            'base_url' => $is_prod ? "https://wse-backend.metabox.hu/api" : "https://dev-wse-backend.metabox.hu/api",
            'timeout' => 10,
            'api_key' => $is_prod ? env('MS_WSE_ENGINE_API_KEY') : env('DEV_MS_WSE_ENGINE_API_KEY'),
            'api_key_header' => 'X-API-Key',
        ],
    ],

    'routes' => [
        [
            'match' => [
                'prefix' => 'ms/inventory',
            ],
            'service' => 'inventory',
            'strip_prefix' => 'ms/inventory',
        ],
        [
            'match' => [
                'prefix' => 'ms/partner',
            ],
            'service' => 'partner',
            'strip_prefix' => 'ms/partner',
        ],
        [
            'match' => [
                'prefix' => 'ms/wse-engine',
            ],
            'service' => 'wse_engine',
            'strip_prefix' => 'ms/wse-engine',
        ],
    ],

    'forward_headers' => [
        'accept',
        'content-type',
        'user-agent',
        'x-request-id',
        'x-correlation-id',
    ],

    'outbound_headers' => [
        'x-gateway' => 'metabox-wms-gateway',
    ],
];
