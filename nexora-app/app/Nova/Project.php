<?php

namespace App\Nova;

use App\Nova\Filters\ActiveStatus;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class Project extends Resource
{
    public static $model = \App\Models\Project::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug', 'type',
    ];

    public static function label(): string
    {
        return 'Проекты';
    }

    public static function singularLabel(): string
    {
        return 'Проект';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Название', 'title')->rules('required', 'max:255'),
            Text::make('Slug', 'slug')
                ->rules('required', 'max:255')
                ->creationRules('unique:projects,slug')
                ->updateRules('unique:projects,slug,{{resourceId}}'),
            Text::make('Тип', 'type')->nullable(),
            Number::make('Год', 'year')->min(2000)->max(2100)->nullable(),
            Text::make('Буква', 'initial')->nullable()->rules('nullable', 'max:8'),

            Textarea::make('Краткое описание', 'short_description')->nullable(),
            Textarea::make('Полное описание', 'full_description')->nullable(),

            Image::make('Обложка', 'cover_image')
                ->disk('public')
                ->path('projects')
                ->nullable(),

            URL::make('Сайт', 'site_url')->nullable(),

            Boolean::make('Активен', 'active'),

            BelongsToMany::make('Теги', 'tags', Tags::class),

            HasMany::make('Изображения', 'images', ProjectImage::class),

            Text::make('SEO Title', 'seo_title')->nullable()->hideFromIndex(),
            Textarea::make('SEO Description', 'seo_description')->nullable()->hideFromIndex(),
            Text::make('SEO Keywords', 'seo_keywords')->nullable()->hideFromIndex(),

            DateTime::make('Создан', 'created_at')->exceptOnForms()->sortable(),
            DateTime::make('Обновлён', 'updated_at')->exceptOnForms()->sortable(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new ActiveStatus,
        ];
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
