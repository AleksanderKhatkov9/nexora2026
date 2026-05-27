<?php

namespace App\Nova\Filters;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class OrderStatus extends Filter
{
    public $name = 'Статус';

    public function apply(NovaRequest $request, $query, $value): Builder
    {
        return $query->where('status', $value);
    }

    public function options(NovaRequest $request): array
    {
        return array_flip(Order::statuses());
    }
}
