<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ActiveStatus extends Filter
{
    public $name = 'Активность';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('active', $value === 'active');
    }

    public function options(NovaRequest $request): array
    {
        return [
            'Активные' => 'active',
            'Неактивные' => 'inactive',
        ];
    }
}
