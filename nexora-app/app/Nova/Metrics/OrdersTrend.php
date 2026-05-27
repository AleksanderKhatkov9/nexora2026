<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class OrdersTrend extends Trend
{
    public function name(): string
    {
        return 'Динамика заявок';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Order::class);
    }

    public function ranges(): array
    {
        return [
            7 => '7 дней',
            14 => '14 дней',
            30 => '30 дней',
            60 => '60 дней',
            90 => '90 дней',
        ];
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
