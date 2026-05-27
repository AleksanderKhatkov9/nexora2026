<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class OrdersTrendByMonth extends Trend
{
    public function name(): string
    {
        return 'Заявки по месяцам';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->countByMonths($request, Order::class);
    }

    public function ranges(): array
    {
        return [
            6 => '6 месяцев',
            12 => '12 месяцев',
            24 => '24 месяца',
        ];
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
