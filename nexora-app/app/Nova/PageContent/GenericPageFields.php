<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Heading;

class GenericPageFields
{
    /**
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public static function make(?object $resource): array
    {
        return [
            Heading::make('Текстовая страница: заголовок и описание — выше, основной текст — ниже.'),
            PageContentField::trix('Текст страницы', 'body', $resource),
        ];
    }
}
