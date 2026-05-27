<?php

namespace App\Nova;

use App\Nova\Filters\ActiveStatus;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Page extends Resource
{
    public static $model = \App\Models\Page::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug',
    ];

    public static function label(): string
    {
        return 'Страницы';
    }

    public static function singularLabel(): string
    {
        return 'Страница';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Название', 'title')->rules('required', 'max:255'),
            Text::make('Slug', 'slug')
                ->rules('required', 'max:255')
                ->creationRules('unique:pages,slug')
                ->updateRules('unique:pages,slug,{{resourceId}}')
                ->help('Уникальный идентификатор: home, projects, contacts'),

            Textarea::make('Описание', 'description')
                ->nullable()
                ->rows(3)
                ->hideFromIndex(),

            Code::make('Контент (JSON)', 'content')
                ->json()
                ->nullable()
                ->help('Структура секций главной: hero, stats, services, cases, benefits, team, clients, departments. Для обычных страниц оставьте пустым.')
                ->hideFromIndex(),

            Image::make('Картинка', 'image')
                ->disk('public')
                ->path('pages')
                ->nullable(),

            Text::make('Ссылка', 'link')->nullable()->hideFromIndex(),

            Boolean::make('Активна', 'active'),

            BelongsToMany::make('Теги', 'tags', Tags::class),

            Text::make('SEO Title', 'seo_title')->nullable()->hideFromIndex(),
            Textarea::make('SEO Description', 'seo_description')->nullable()->hideFromIndex(),
            Text::make('SEO Keywords', 'seo_keywords')->nullable()->hideFromIndex(),

            DateTime::make('Создана', 'created_at')->sortable()->exceptOnForms(),
            DateTime::make('Обновлена', 'updated_at')->sortable()->exceptOnForms(),
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
