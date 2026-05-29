<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Panel;

class PricingPageFields
{
    /**
     * @return array<int, Panel|\Laravel\Nova\Fields\Field>
     */
    public static function make(?object $resource): array
    {
        $f = fn (string $label, string $path) => PageContentField::text($label, $path, $resource);
        $ta = fn (string $label, string $path, int $rows = 2) => PageContentField::textarea($label, $path, $resource, $rows);
        $lines = fn (string $label, string $path) => PageContentField::lines($label, $path, $resource);

        return [
            Heading::make('Тарифы и тексты страницы «Цены».'),

            Panel::make('Вступление', [
                $f('Подзаголовок', 'intro.eyebrow'),
                $ta('Вводный текст', 'intro.lead', 3),
            ]),

            Panel::make('Тариф 1', self::planFields($resource, 0)),
            Panel::make('Тариф 2 (рекомендуемый)', self::planFields($resource, 1, true)),
            Panel::make('Тариф 3', self::planFields($resource, 2)),

            Panel::make('Дополнительные услуги', self::extraFields($resource)),

            Panel::make('Примечание внизу страницы', [
                $ta('Текст примечания', 'note', 3),
            ]),
        ];
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function planFields(?object $resource, int $index, bool $withFeatured = false): array
    {
        $p = "plans.{$index}";
        $fields = [
            PageContentField::text('Название тарифа', "{$p}.name", $resource),
            PageContentField::text('Цена', "{$p}.price", $resource),
            PageContentField::text('Валюта', "{$p}.currency", $resource),
            PageContentField::text('Период (например: от)', "{$p}.period", $resource),
            PageContentField::textarea('Краткое описание', "{$p}.description", $resource, 2),
            PageContentField::lines('Что входит (по строкам)', "{$p}.features", $resource),
            PageContentField::text('Текст кнопки', "{$p}.cta", $resource),
        ];

        if ($withFeatured) {
            $fields[] = Boolean::make('Выделить тариф', "page_content__{$p}__featured")
                ->resolveUsing(fn () => (bool) data_get($resource?->content, "{$p}.featured"))
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) use ($p) {
                    $content = is_array($model->content) ? $model->content : [];
                    data_set($content, "{$p}.featured", $request->boolean($requestAttribute));
                    $model->content = $content;
                });

            $fields[] = PageContentField::text('Бейдж (например: Популярный)', "{$p}.badge", $resource);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function extraFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 3; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Доп. услуга {$n} — заголовок", "extras.{$i}.title", $resource);
            $fields[] = PageContentField::text("Доп. услуга {$n} — описание", "extras.{$i}.text", $resource);
        }

        return $fields;
    }
}
