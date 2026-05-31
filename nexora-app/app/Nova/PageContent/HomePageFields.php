<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Panel;

class HomePageFields
{
    /**
     * @return array<int, \Laravel\Nova\Panel|\Laravel\Nova\Fields\Field>
     */
    public static function make(?object $resource): array
    {
        $f = fn (string $label, string $path, bool $required = false) => PageContentField::text($label, $path, $resource, $required);
        $ta = fn (string $label, string $path, int $rows = 3) => PageContentField::textarea($label, $path, $resource, $rows);
        $lines = fn (string $label, string $path) => PageContentField::lines($label, $path, $resource);

        $fields = [
            Heading::make('Редактирование блоков главной страницы. Изменения сразу видны на сайте после сохранения.'),

            Panel::make('Первый экран (Hero)', [
                $f('Подзаголовок', 'hero.eyebrow'),
                $f('Заголовок', 'hero.title', true),
                $ta('Текст под заголовком', 'hero.lead', 4),
            ]),

            Panel::make('Цифры (статистика)', self::statFields($resource)),

            Panel::make('Услуги', self::serviceFields($resource)),

            Panel::make('Кейсы / портфолио на главной', self::caseFields($resource)),

            Panel::make('Преимущества', self::benefitFields($resource)),

            Panel::make('Этапы личной работы', self::teamFields($resource)),

            Panel::make('Клиенты', [
                PageContentField::clients('Логотипы / названия клиентов', 'clients', $resource),
            ]),

            Panel::make('Направления работы', self::departmentFields($resource)),
        ];

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function statFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 3; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Цифра {$n}", "stats.{$i}.value", $resource);
            $fields[] = PageContentField::text("Подпись {$n}", "stats.{$i}.label", $resource);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function serviceFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 5; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("№ {$n}", "services.{$i}.num", $resource);
            $fields[] = PageContentField::text("Заголовок {$n}", "services.{$i}.title", $resource);
            $fields[] = PageContentField::textarea("Описание {$n}", "services.{$i}.text", $resource, 2);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function caseFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 3; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Кейс {$n} — тег", "cases.{$i}.tag", $resource);
            $fields[] = PageContentField::text("Кейс {$n} — заголовок", "cases.{$i}.title", $resource);
            $fields[] = PageContentField::text("Кейс {$n} — клиент", "cases.{$i}.client", $resource);
            $fields[] = PageContentField::lines("Кейс {$n} — пункты списка", "cases.{$i}.points", $resource);
            $fields[] = PageContentField::text("Кейс {$n} — результат", "cases.{$i}.result", $resource);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function benefitFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 4; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Преимущество {$n} — заголовок", "benefits.{$i}.title", $resource);
            $fields[] = PageContentField::textarea("Преимущество {$n} — текст", "benefits.{$i}.text", $resource, 2);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function teamFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 4; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Этап {$n} — номер", "team.{$i}.initial", $resource);
            $fields[] = PageContentField::text("Этап {$n} — название", "team.{$i}.name", $resource);
            $fields[] = PageContentField::text("Этап {$n} — описание", "team.{$i}.role", $resource);
        }

        return $fields;
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    protected static function departmentFields(?object $resource): array
    {
        $fields = [];

        for ($i = 0; $i < 5; $i++) {
            $n = $i + 1;
            $fields[] = PageContentField::text("Направление {$n} — иконка (emoji)", "departments.{$i}.icon", $resource);
            $fields[] = PageContentField::text("Направление {$n} — название", "departments.{$i}.title", $resource);
            $fields[] = PageContentField::textarea("Направление {$n} — описание", "departments.{$i}.text", $resource, 2);
        }

        return $fields;
    }
}
