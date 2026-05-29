<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Heading;

class ListingPageFields
{
    /**
     * @return array<int, Heading>
     */
    public static function make(string $slug): array
    {
        $hint = match ($slug) {
            'news' => 'Записи раздела редактируются в «Блог» (тип: Новость). Здесь — только заголовок, описание и SEO для списка /news.',
            'articles' => 'Записи раздела — в «Блог» (тип: Статья). Здесь — заголовок и SEO для списка /articles.',
            'projects' => 'Карточки портфолио — в «Проекты». Здесь — заголовок и описание страницы /projects.',
            default => 'Пункт меню без отдельного текста. Редактируйте подпись в блоке «Меню и подвал».',
        };

        return [
            Heading::make($hint),
        ];
    }
}
