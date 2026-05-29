<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class PageSlugFilter extends Filter
{
    public $name = 'Тип страницы';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        if ($value === 'system') {
            return $query->whereIn('slug', ['home', 'pricing', 'projects', 'news', 'articles', 'nav-services']);
        }

        if ($value === 'menu') {
            return $query->where('show_in_menu', true);
        }

        return $query->where('slug', $value);
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Системные (все)' => 'system',
            'Главная' => 'home',
            'Цены' => 'pricing',
            'Портфолио (страница)' => 'projects',
            'Новости (раздел)' => 'news',
            'Статьи (раздел)' => 'articles',
            'В меню' => 'menu',
        ];
    }
}
