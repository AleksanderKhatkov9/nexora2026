<?php

namespace App\Nova;

use App\Support\PublicAssetUrl;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProjectImage extends Resource
{
    public static $model = \App\Models\ProjectImage::class;

    public static $title = 'path';

    public static $search = [
        'id', 'path', 'alt',
    ];

    public static $displayInNavigation = false;

    public static function label(): string
    {
        return 'Изображения проекта';
    }

    public static function singularLabel(): string
    {
        return 'Изображение';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Проект', 'project', Project::class)
                ->rules('required')
                ->searchable()
                ->hideWhenCreating(fn (NovaRequest $request) => (bool) $request->viaResource()),

            Image::make('Файл', 'path')
                ->disk('public')
                ->path('projects/gallery')
                ->rules('required')
                ->thumbnail(fn ($value) => PublicAssetUrl::url($value))
                ->preview(fn ($value) => PublicAssetUrl::url($value)),

            Text::make('Alt', 'alt')->nullable(),

            Number::make('Порядок', 'sort_order')->min(0)->default(0),

            DateTime::make('Создан', 'created_at')->exceptOnForms(),
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
