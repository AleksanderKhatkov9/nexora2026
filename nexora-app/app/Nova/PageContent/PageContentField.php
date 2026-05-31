<?php

namespace App\Nova\PageContent;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class PageContentField
{
    /**
     * @return array<int, Field>
     */
    public static function forResource(?object $resource, string $slug): array
    {
        return match ($slug) {
            'home' => HomePageFields::make($resource),
            'pricing' => PricingPageFields::make($resource),
            'reviews' => ReviewsPageFields::make($resource),
            'news', 'articles', 'projects', 'nav-services' => ListingPageFields::make($slug),
            default => GenericPageFields::make($resource),
        };
    }

    public static function text(string $label, string $path, ?object $resource, bool $required = false): Text
    {
        $field = Text::make($label, self::attribute($path))
            ->resolveUsing(fn () => data_get($resource?->content, $path))
            ->fillUsing(self::fillUsing($path));

        return $required ? $field->rules('required', 'max:500') : $field->nullable();
    }

    public static function textarea(string $label, string $path, ?object $resource, int $rows = 3): Textarea
    {
        return Textarea::make($label, self::attribute($path))
            ->rows($rows)
            ->resolveUsing(fn () => data_get($resource?->content, $path))
            ->fillUsing(self::fillUsing($path))
            ->nullable();
    }

    public static function trix(string $label, string $path, ?object $resource): Trix
    {
        return Trix::make($label, self::attribute($path))
            ->resolveUsing(fn () => data_get($resource?->content, $path))
            ->fillUsing(self::fillUsing($path))
            ->nullable()
            ->alwaysShow();
    }

    /**
     * @param  array<int, string>|null  $lines
     */
    public static function lines(string $label, string $path, ?object $resource, ?array $lines = null): Textarea
    {
        return Textarea::make($label, self::attribute($path))
            ->rows(4)
            ->resolveUsing(function () use ($path, $resource, $lines) {
                $value = data_get($resource?->content, $path, $lines);

                if (is_array($value)) {
                    return implode("\n", $value);
                }

                return $value;
            })
            ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($path) {
                $raw = $request->input($requestAttribute);
                $content = is_array($model->content) ? $model->content : [];

                if ($raw === null || trim((string) $raw) === '') {
                    data_set($content, $path, []);
                } else {
                    $items = preg_split("/\r\n|\r|\n/", (string) $raw) ?: [];
                    $items = array_values(array_filter(array_map('trim', $items), fn ($line) => $line !== ''));
                    data_set($content, $path, $items);
                }

                $model->content = $content;
            })
            ->nullable()
            ->help('Каждый пункт — с новой строки.');
    }

    public static function clients(string $label, string $path, ?object $resource): Textarea
    {
        return Textarea::make($label, self::attribute($path))
            ->rows(3)
            ->resolveUsing(function () use ($path, $resource) {
                $value = data_get($resource?->content, $path, []);

                if (is_array($value)) {
                    return implode(', ', $value);
                }

                return $value;
            })
            ->fillUsing(function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($path) {
                $raw = $request->input($requestAttribute);
                $content = is_array($model->content) ? $model->content : [];

                if ($raw === null || trim((string) $raw) === '') {
                    data_set($content, $path, []);
                } else {
                    $items = array_map('trim', explode(',', (string) $raw));
                    $items = array_values(array_filter($items, fn ($item) => $item !== ''));
                    data_set($content, $path, $items);
                }

                $model->content = $content;
            })
            ->nullable()
            ->help('Названия клиентов через запятую.');
    }

    protected static function attribute(string $path): string
    {
        return 'page_content__'.str_replace('.', '__', $path);
    }

    protected static function fillUsing(string $path): callable
    {
        return function (NovaRequest $request, $model, $attribute, $requestAttribute) use ($path) {
            $content = is_array($model->content) ? $model->content : [];
            data_set($content, $path, $request->input($requestAttribute));
            $model->content = $content;
        };
    }
}
