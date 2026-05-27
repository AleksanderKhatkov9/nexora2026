<?php

return [
    'webmaster' => [
        'enabled' => env('YANDEX_WEBMASTER_ENABLED', false),
        'oauth_token' => env('YANDEX_WEBMASTER_OAUTH_TOKEN'),
        'host_id' => env('YANDEX_WEBMASTER_HOST_ID'),
        'site_url' => env('YANDEX_WEBMASTER_SITE_URL', env('APP_URL')),
        'cache_ttl' => (int) env('YANDEX_WEBMASTER_CACHE_TTL', 3600),
        'api_base' => 'https://api.webmaster.yandex.net/v4',
    ],
];
