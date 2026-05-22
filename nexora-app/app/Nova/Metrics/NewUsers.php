<?php

namespace App\Nova\Metrics;

use App\Models\User;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class NewUsers extends Value
{
    public function name(): string
    {
        return 'Users';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->result(User::query()->count());
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor(): DateTimeInterface|null
    {
        return null;
    }
}