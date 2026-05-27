<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class OrdersByStatus extends Partition
{
    public function name(): string
    {
        return 'Заявки по статусам';
    }

    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Order::class, 'status')
            ->label(fn (string $value) => Order::statuses()[$value] ?? $value);
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
