<?php

namespace App\Nova;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserRole extends Resource
{
    public static $model = \App\Models\UserRole::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title',
    ];

    public static function label(): string
    {
        return 'Роли';
    }

    public static function singularLabel(): string
    {
        return 'Роль';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Название', 'title')
                ->rules('required', 'max:255'),

            HasMany::make('Пользователи', 'users', User::class),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
