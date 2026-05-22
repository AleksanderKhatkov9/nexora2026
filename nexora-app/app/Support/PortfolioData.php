<?php

namespace App\Support;

class PortfolioData
{
    /**
     * @return array<int, array{slug: string, name: string}>
     */
    public static function tags(): array
    {
        return [
            ['slug' => 'all', 'name' => 'Все проекты'],
            ['slug' => 'crm', 'name' => 'CRM и личные кабинеты'],
            ['slug' => 'corporate', 'name' => 'Корпоративные сайты'],
            ['slug' => 'catalog', 'name' => 'Интернет-каталог'],
            ['slug' => 'shop', 'name' => 'Интернет-магазин'],
            ['slug' => 'portal', 'name' => 'Порталы и сервисы'],
            ['slug' => 'landing', 'name' => 'Landing page'],
        ];
    }

    /**
     * @return array<int, array{slug: string, type: string, year: int, title: string, tag: string, initial: string}>
     */
    public static function projects(): array
    {
        return [
            ['slug' => 'ecotravel', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Туристические агентства Ecotravel', 'tag' => 'corporate', 'initial' => 'E'],
            ['slug' => 'impulse', 'type' => 'Интернет-каталог', 'year' => 2026, 'title' => 'Энергетические пастилки Impulse', 'tag' => 'catalog', 'initial' => 'I'],
            ['slug' => 'inklesmed', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Клиника «Инклесмед»', 'tag' => 'corporate', 'initial' => 'И'],
            ['slug' => 'beltd', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Общественное объединение «БелТД»', 'tag' => 'corporate', 'initial' => 'Б'],
            ['slug' => 'pgs', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Группа компаний «ПГС»', 'tag' => 'corporate', 'initial' => 'П'],
            ['slug' => 'medstrahovka', 'type' => 'Порталы и сервисы', 'year' => 2026, 'title' => 'Страховой брокер «Медстраховка»', 'tag' => 'portal', 'initial' => 'М'],
            ['slug' => 'lisfin', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Лизинговая компания «ЛИСФИН»', 'tag' => 'corporate', 'initial' => 'Л'],
            ['slug' => 'waldorf', 'type' => 'Интернет-каталог', 'year' => 2026, 'title' => 'Waldorf Astoria Minsk', 'tag' => 'catalog', 'initial' => 'W'],
            ['slug' => 'tihaya-gavan', 'type' => 'Интернет-каталог', 'year' => 2026, 'title' => 'Тихая Гавань', 'tag' => 'catalog', 'initial' => 'Т'],
            ['slug' => 'tpr', 'type' => 'Интернет-каталог', 'year' => 2026, 'title' => 'Технологии промышленного ремонта', 'tag' => 'catalog', 'initial' => 'Т'],
            ['slug' => 'sokar', 'type' => 'Корпоративный сайт', 'year' => 2026, 'title' => 'Сокар Констракшн', 'tag' => 'corporate', 'initial' => 'S'],
            ['slug' => 'old-mouse', 'type' => 'Порталы и сервисы', 'year' => 2025, 'title' => 'Old Mouse', 'tag' => 'portal', 'initial' => 'O'],
            ['slug' => 'helen-valery', 'type' => 'Интернет-магазин', 'year' => 2025, 'title' => 'Helen & Valery', 'tag' => 'shop', 'initial' => 'H'],
            ['slug' => 'iceberg', 'type' => 'Landing page', 'year' => 2025, 'title' => 'IcebergEnergy', 'tag' => 'landing', 'initial' => 'I'],
            ['slug' => 'gohome', 'type' => 'Порталы и сервисы', 'year' => 2025, 'title' => 'Портал недвижимости GoHome.by', 'tag' => 'portal', 'initial' => 'G'],
            ['slug' => 'priorlife', 'type' => 'CRM и личные кабинеты', 'year' => 2025, 'title' => 'Страховая компания PriorLife', 'tag' => 'crm', 'initial' => 'P'],
            ['slug' => 'usapostline', 'type' => 'Порталы и сервисы', 'year' => 2024, 'title' => 'USAPostLine — доставка товаров', 'tag' => 'portal', 'initial' => 'U'],
            ['slug' => 'mikro-leasing', 'type' => 'CRM и личные кабинеты', 'year' => 2024, 'title' => 'Микро Лизинг — личные кабинеты', 'tag' => 'crm', 'initial' => 'М'],
        ];
    }
}
