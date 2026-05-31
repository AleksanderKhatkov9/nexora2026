<?php

return [
    'order_notification_email' => env('ORDER_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),

    'seo' => [
        'default_title' => env('SEO_DEFAULT_TITLE', 'Nexora — частная разработка сайтов'),
        'default_description' => env(
            'SEO_DEFAULT_DESCRIPTION',
            'Nexora — личная разработка и сопровождение веб-проектов под ключ. Современные сайты, порталы и автоматизация бизнес-процессов.'
        ),
        'default_image' => env('SEO_DEFAULT_IMAGE'),
    ],
];
