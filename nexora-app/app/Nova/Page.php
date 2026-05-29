<?php

namespace App\Nova;

use App\Models\Page as PageModel;
use App\Nova\Filters\ActiveStatus;
use App\Nova\Filters\PageSlugFilter;
use App\Nova\PageContent\PageContentField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Page extends Resource
{
    public static $model = PageModel::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug',
    ];

    public static $priority = 1;

    public static function label(): string
    {
        return 'Страницы сайта';
    }

    public static function singularLabel(): string
    {
        return 'Страница';
    }

    public function fields(NovaRequest $request): array
    {
        $fields = [
            ID::make()->sortable(),

            Text::make('Страница', 'title')
                ->sortable()
                ->rules('required', 'max:255')
                ->help('Заголовок для админки и SEO (если не задан отдельный SEO Title).'),

            Text::make('Slug', 'slug')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:pages,slug')
                ->updateRules('unique:pages,slug,{{resourceId}}')
                ->help('home — главная, pricing — цены, news/articles — разделы блога. Не меняйте slug системных страниц без необходимости.'),

            Boolean::make('Активна', 'active')->sortable(),

            URL::make('На сайте', fn () => $this->publicUrl())
                ->displayUsing(fn () => $this->publicUrl())
                ->onlyOnDetail(),

            Panel::make('Основное', [
                Textarea::make('Краткое описание', 'description')
                    ->nullable()
                    ->rows(3)
                    ->help('Подзаголовок под H1 на странице и текст для SEO.'),

                Image::make('Картинка', 'image')
                    ->disk('public')
                    ->path('pages')
                    ->nullable(),

                Text::make('Внешняя ссылка', 'link')
                    ->nullable()
                    ->help('Только для типа меню «Внешняя ссылка».'),
            ]),

            Panel::make('Меню (шапка сайта)', [
                Boolean::make('Показывать в меню', 'show_in_menu'),

                Number::make('Порядок', 'menu_order')
                    ->min(0)
                    ->default(0),

                Text::make('Подпись в меню', 'menu_label')
                    ->nullable()
                    ->help('Пусто — используется название страницы.'),

                Select::make('Тип пункта', 'menu_type')
                    ->options(PageModel::MENU_TYPES)
                    ->default(PageModel::MENU_TYPE_ROUTE)
                    ->displayUsingLabels(),

                Text::make('Якорь', 'menu_hash')
                    ->nullable()
                    ->help('Для якоря на главной, например: #services'),
            ]),

            Panel::make('Подвал сайта', [
                Boolean::make('Показывать в подвале', 'show_in_footer'),

                Select::make('Группа', 'footer_group')
                    ->options(PageModel::FOOTER_GROUPS)
                    ->nullable()
                    ->displayUsingLabels(),

                Number::make('Порядок', 'footer_order')->min(0)->default(0),

                Text::make('Подпись', 'footer_label')->nullable(),
            ]),

            Panel::make('SEO', [
                Text::make('SEO Title', 'seo_title')->nullable(),
                Textarea::make('SEO Description', 'seo_description')->nullable()->rows(2),
                Text::make('SEO Keywords', 'seo_keywords')->nullable(),
            ]),

            Code::make('Контент (JSON)', 'content')
                ->json()
                ->readonly()
                ->onlyOnDetail()
                ->help('Для разработчиков. Редактируйте блоки в форме редактирования.'),

            DateTime::make('Создана', 'created_at')->sortable()->exceptOnForms(),
            DateTime::make('Обновлена', 'updated_at')->sortable()->exceptOnForms(),
        ];

        if ($this->showsContentEditor($request)) {
            $fields[] = Panel::make('Контент', $this->contentFields($request));
        }

        return $fields;
    }

    protected function showsContentEditor(NovaRequest $request): bool
    {
        return $request->isCreateOrAttachRequest()
            || $request->isUpdateOrUpdateAttachedRequest();
    }

    /**
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>
     */
    protected function contentFields(NovaRequest $request): array
    {
        $resource = $this->resource;
        $knownSlugs = ['home', 'pricing', 'news', 'articles', 'projects', 'nav-services'];

        $groups = [
            'home' => PageContentField::forResource($resource, 'home'),
            'pricing' => PageContentField::forResource($resource, 'pricing'),
            'news' => PageContentField::forResource($resource, 'news'),
            'articles' => PageContentField::forResource($resource, 'articles'),
            'projects' => PageContentField::forResource($resource, 'projects'),
            'nav-services' => PageContentField::forResource($resource, 'nav-services'),
        ];

        $fields = [
            Heading::make('Поля зависят от slug страницы. После смены slug сохраните и откройте снова.'),
        ];

        foreach ($groups as $slug => $groupFields) {
            array_push($fields, ...$this->wrapSlugGroup($groupFields, [$slug]));
        }

        array_push(
            $fields,
            ...$this->wrapSlugGroup(
                PageContentField::forResource($resource, 'generic'),
                ['__generic__'],
                $knownSlugs
            )
        );

        return $fields;
    }

    /**
     * @param  array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>  $items
     * @param  array<int, string>  $slugs
     * @param  array<int, string>|null  $exceptSlugs
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel>
     */
    protected function wrapSlugGroup(array $items, array $slugs, ?array $exceptSlugs = null): array
    {
        $result = [];

        foreach ($items as $item) {
            if ($item instanceof Panel) {
                $children = [];

                foreach ($item->data ?? [] as $child) {
                    $children[] = $this->showWhenSlug($child, $slugs, $exceptSlugs);
                }

                $result[] = Panel::make($item->name, $children);

                continue;
            }

            $result[] = $this->showWhenSlug($item, $slugs, $exceptSlugs);
        }

        return $result;
    }

    /**
     * @param  array<int, string>  $slugs
     * @param  array<int, string>|null  $exceptSlugs
     */
    protected function showWhenSlug($field, array $slugs, ?array $exceptSlugs = null)
    {
        return $field->dependsOn(['slug'], function ($field, NovaRequest $request, FormData $formData) use ($slugs, $exceptSlugs) {
            $slug = $formData->slug ?? $this->resource?->slug;

            $visible = in_array('__generic__', $slugs, true)
                ? ! in_array($slug, $exceptSlugs ?? [], true)
                : in_array($slug, $slugs, true);

            $visible ? $field->show() : $field->hide();
        });
    }

    protected function publicUrl(): ?string
    {
        if (! $this->resource?->slug) {
            return null;
        }

        return url($this->resource->menuPath());
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new PageSlugFilter,
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
