<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OrdersByChannel extends Partition
{
    public function name(): string
    {
        return 'Способ связи';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class, 'channel')
            ->label(fn (?string $value) => $value
                ? (Order::channels()[$value] ?? $value)
                : 'Не указан');
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
