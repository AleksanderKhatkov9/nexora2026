<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tags extends Resource
{
    public static $model = \App\Models\Tags::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'slug',
    ];

    public static function label(): string
    {
        return 'Теги';
    }

    public static function singularLabel(): string
    {
        return 'Тег';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Название', 'name')->rules('required', 'max:255'),
            Text::make('Slug', 'slug')
                ->rules('required', 'max:255')
                ->creationRules('unique:tags,slug')
                ->updateRules('unique:tags,slug,{{resourceId}}'),

            BelongsToMany::make('Проекты', 'projects', Project::class),

            DateTime::make('Создан', 'created_at')->sortable()->exceptOnForms(),
            DateTime::make('Обновлён', 'updated_at')->sortable()->exceptOnForms(),
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
