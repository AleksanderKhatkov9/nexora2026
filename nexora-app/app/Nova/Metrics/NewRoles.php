<?php

namespace App\Nova\Metrics;

use App\Models\UserRole;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class NewRoles extends Value
{
    public function name(): string
    {
        return 'Roles';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->result(UserRole::query()->count());
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