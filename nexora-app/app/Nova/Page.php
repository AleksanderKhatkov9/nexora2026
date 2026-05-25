<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Page>
     */
    public static $model = \App\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
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

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Title', 'title')->rules('required', 'max:255'),
            Text::make('Slug', 'slug')->rules('required', 'max:255'),
            Trix::make('Description', 'description'),
            Trix::make('Content', 'content'),
            Image::make('Картинка', 'image')
                ->disk('public')
                ->path('pages')
                ->nullable(),
            Text::make('Link', 'link')->nullable(),
            Boolean::make('Active', 'active'),

            BelongsToMany::make('Теги', 'tags', Tags::class),

            Text::make('SEO Title', 'seo_title')->nullable(),
            Textarea::make('SEO Description', 'seo_description')->nullable(),
            Text::make('SEO Keywords', 'seo_keywords')->nullable(),
            DateTime::make('Дата создания', 'created_at')->sortable()->exceptOnForms(),
            DateTime::make('Дата обновления', 'updated_at')->sortable()->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
