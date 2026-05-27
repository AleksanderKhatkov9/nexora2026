<?php

namespace App\Nova\Metrics;

use App\Models\Order;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Progress;
use Laravel\Nova\Metrics\ProgressResult;

class OrdersCompletionProgress extends Progress
{
    public function name(): string
    {
        return 'Завершённые заявки';
    }

    public function calculate(NovaRequest $request): ProgressResult
    {
        $total = Order::query()->count();

        if ($total === 0) {
            return $this->result(0, 1);
        }

        $completed = Order::query()
            ->where('status', Order::STATUS_DONE)
            ->count();

        return $this->result($completed, $total);
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
