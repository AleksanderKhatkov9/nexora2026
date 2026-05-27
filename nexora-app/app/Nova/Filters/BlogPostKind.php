<?php

namespace App\Nova\Filters;

use App\Models\BlogPost;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogPostKind extends Filter
{
    public $name = 'Тип записи';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('kind', $value);
    }

    public function options(NovaRequest $request): array
    {
        return array_flip(BlogPost::KINDS);
    }
}
