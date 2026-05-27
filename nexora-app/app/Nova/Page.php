<?php

namespace App\Nova;

use App\Nova\Filters\ActiveStatus;
use App\Models\Page as PageModel;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
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
                ->help('Уникальный идентификатор: home, projects, pricing, contacts'),

            Textarea::make('Описание', 'description')
                ->nullable()
                ->rows(3)
                ->hideFromIndex(),

            Code::make('Контент (JSON)', 'content')
                ->json()
                ->nullable()
                ->help('JSON: home — hero, stats, services…; pricing — intro, plans, extras, note. Для простых страниц оставьте пустым.')
                ->hideFromIndex(),

            Image::make('Картинка', 'image')
                ->disk('public')
                ->path('pages')
                ->nullable(),

            Text::make('Ссылка', 'link')
                ->nullable()
                ->hideFromIndex()
                ->help('Для типа «Внешняя ссылка» — полный URL'),

            Boolean::make('Активна', 'active'),

            Boolean::make('В меню', 'show_in_menu'),

            Boolean::make('В подвале', 'show_in_footer'),

            Select::make('Группа в подвале', 'footer_group')
                ->options(PageModel::FOOTER_GROUPS)
                ->nullable()
                ->displayUsingLabels()
                ->hideFromIndex()
                ->help('Разделы, Услуги или Правовая информация'),

            Number::make('Порядок в подвале', 'footer_order')
                ->min(0)
                ->default(0)
                ->hideFromIndex(),

            Text::make('Подпись в подвале', 'footer_label')
                ->nullable()
                ->hideFromIndex()
                ->help('Если пусто — используется подпись меню или название'),

            Number::make('Порядок в меню', 'menu_order')
                ->min(0)
                ->default(0)
                ->hideFromIndex(),

            Text::make('Подпись в меню', 'menu_label')
                ->nullable()
                ->hideFromIndex()
                ->help('Если пусто — используется название страницы'),

            Select::make('Тип пункта меню', 'menu_type')
                ->options(PageModel::MENU_TYPES)
                ->default(PageModel::MENU_TYPE_ROUTE)
                ->displayUsingLabels()
                ->hideFromIndex(),

            Text::make('Якорь (#секция)', 'menu_hash')
                ->nullable()
                ->hideFromIndex()
                ->help('Для типа «Якорь на главной», например: #services'),

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
