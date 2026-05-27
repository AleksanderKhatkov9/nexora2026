<?php

return [
    'order_notification_email' => env('ORDER_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),

    'seo' => [
        'default_title' => env('SEO_DEFAULT_TITLE', 'Nexora — создание сайтов и веб-разработка'),
        'default_description' => env(
            'SEO_DEFAULT_DESCRIPTION',
            'Nexora — разработка и сопровождение веб-проектов под ключ. Современные сайты, порталы и автоматизация бизнес-процессов.'
        ),
        'default_image' => env('SEO_DEFAULT_IMAGE'),
    ],
];
