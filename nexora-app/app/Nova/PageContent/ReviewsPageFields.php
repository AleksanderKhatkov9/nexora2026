<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Panel;

class ReviewsPageFields
{
    /**
     * @return array<int, \Laravel\Nova\Panel|\Laravel\Nova\Fields\Field>
     */
    public static function make(?object $resource): array
    {
        return [
            Heading::make('Страница отзывов: вводный блок и тестовые отзывы клиентов.'),

            Panel::make('Вводный блок', [
                PageContentField::text('Метка', 'intro.eyebrow', $resource),
                PageContentField::textarea('Описание', 'intro.lead', $resource, 3),
            ]),

            Panel::make('Отзывы', self::reviewFields($resource)),
        ];
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function reviewFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 6; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Отзыв {$n} — компания", "reviews.{$i}.company", $resource);
            $fields[] = PageContentField::text("Отзыв {$n} — имя", "reviews.{$i}.name", $resource);
            $fields[] = PageContentField::text("Отзыв {$n} — должность", "reviews.{$i}.role", $resource);
            $fields[] = PageContentField::textarea("Отзыв {$n} — текст", "reviews.{$i}.text", $resource, 4);
            $fields[] = PageContentField::text("Отзыв {$n} — результат", "reviews.{$i}.result", $resource);
        }

        return $fields;
    }
}
