<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class NewOrders extends Value
{
    public function name(): string
    {
        return 'Новые заявки';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count(
            $request,
            Order::query()->where('status', Order::STATUS_NEW)
        );
    }

    public function ranges(): array
    {
        return [
            7 => '7 дней',
            30 => '30 дней',
            60 => '60 дней',
            365 => '365 дней',
            'TODAY' => 'Сегодня',
            'MTD' => 'С начала месяца',
        ];
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
