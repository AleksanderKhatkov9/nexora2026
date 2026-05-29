<?php

namespace App\Nova;

use App\Models\BlogPost as BlogPostModel;
use App\Nova\Filters\ActiveStatus;
use App\Nova\Filters\BlogPostKind;
use App\Support\PublicAssetUrl;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class BlogPost extends Resource
{
    public static $model = BlogPostModel::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug', 'author',
    ];

    public static function label(): string
    {
        return 'Блог';
    }

    public static function singularLabel(): string
    {
        return 'Запись';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Select::make('Раздел', 'kind')
                ->options(BlogPostModel::KINDS)
                ->rules('required')
                ->displayUsingLabels()
                ->sortable()
                ->help('Новость → /news/…, Статья → /articles/…'),

            Text::make('Заголовок', 'title')->rules('required', 'max:255')->sortable(),

            Text::make('Slug', 'slug')
                ->rules('required', 'max:255')
                ->creationRules('unique:blog_posts,slug')
                ->updateRules('unique:blog_posts,slug,{{resourceId}}'),

            Boolean::make('Опубликована', 'active')->sortable(),

            DateTime::make('Дата', 'published_at')
                ->sortable()
                ->nullable()
                ->help('Сортировка в ленте'),

            URL::make('На сайте', fn () => $this->publicUrl())
                ->displayUsing(fn () => $this->publicUrl())
                ->onlyOnDetail(),

            Panel::make('Материал', [
                Image::make('Обложка', 'cover_image')
                    ->disk('public')
                    ->path('blog')
                    ->nullable()
                    ->thumbnail(fn ($value) => PublicAssetUrl::url($value))
                    ->preview(fn ($value) => PublicAssetUrl::url($value)),

                Textarea::make('Анонс', 'excerpt')
                    ->nullable()
                    ->rows(3)
                    ->help('Краткий текст в списке новостей/статей.'),

                Trix::make('Текст', 'content')
                    ->nullable()
                    ->alwaysShow()
                    ->hideFromIndex(),

                Text::make('Автор', 'author')->nullable(),
            ]),

            Panel::make('SEO', [
                Text::make('SEO Title', 'seo_title')->nullable(),
                Textarea::make('SEO Description', 'seo_description')->nullable()->rows(2),
                Text::make('SEO Keywords', 'seo_keywords')->nullable(),
            ]),

            DateTime::make('Создана', 'created_at')->exceptOnForms()->sortable(),
            DateTime::make('Обновлена', 'updated_at')->exceptOnForms()->sortable(),
        ];
    }

    protected function publicUrl(): ?string
    {
        if (! $this->resource?->slug) {
            return null;
        }

        return url($this->resource->publicPath());
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new BlogPostKind,
            new ActiveStatus,
        ];
    }
}
