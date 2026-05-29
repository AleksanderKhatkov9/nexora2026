<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Драйверы внешних API
    |--------------------------------------------------------------------------
    |
    | Каждый драйвер описывает поля для Nova и класс проверки подключения.
    | Запись в таблице api_integrations создаётся сидером ApiIntegrationSeeder.
    |
    */
    'drivers' => [
        'yandex_webmaster' => [
            'name' => 'Яндекс.Вебмастер',
            'description' => 'Поисковая статистика: клики, показы, индексация, ИКС. Дашборд Nova «Посещаемость (Яндекс)».',
            'credential_fields' => [
                'oauth_token' => [
                    'label' => 'OAuth-токен',
                    'type' => 'password',
                    'help' => 'Получить на oauth.yandex.ru после регистрации приложения.',
                ],
            ],
            'setting_fields' => [
                'site_url' => [
                    'label' => 'URL сайта',
                    'type' => 'text',
                    'help' => 'Домен для автопоиска host_id. По умолчанию APP_URL.',
                    'default' => env('APP_URL'),
                ],
                'host_id' => [
                    'label' => 'Host ID',
                    'type' => 'text',
                    'help' => 'Необязательно. Формат: https:example.com:443',
                ],
                'cache_ttl' => [
                    'label' => 'Кеш ответов API (сек)',
                    'type' => 'number',
                    'default' => 3600,
                ],
            ],
            'tester' => App\Integrations\Testers\YandexWebmasterTester::class,
            'fallback' => [
                'enabled' => env('YANDEX_WEBMASTER_ENABLED', false),
                'credentials' => [
                    'oauth_token' => env('YANDEX_WEBMASTER_OAUTH_TOKEN'),
                ],
                'settings' => [
                    'site_url' => env('YANDEX_WEBMASTER_SITE_URL'),
                    'host_id' => env('YANDEX_WEBMASTER_HOST_ID'),
                    'cache_ttl' => env('YANDEX_WEBMASTER_CACHE_TTL'),
                ],
            ],
        ],
    ],
];
