<?php

namespace App\Nova\Metrics;

use App\Models\Project;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class ActiveProjects extends Value
{
    public function name(): string
    {
        return 'Активные проекты';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->result(
            Project::query()->where('active', true)->count()
        );
    }

    public function ranges(): array
    {
        return [];
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return null;
    }
}
