<?php

namespace App\Nova\Metrics\YandexWebmaster;

use App\Nova\Metrics\Concerns\InteractsWithYandexWebmaster;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class YandexSiteQualityIndex extends Value
{
    use InteractsWithYandexWebmaster;

    public function name(): string
    {
        return 'ИКС (SQI)';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->withYandexWebmaster(
            function ($service) {
                $summary = $service->getSummary();

                return (new ValueResult)
                    ->result((int) ($summary['sqi'] ?? 0));
            },
            $this->unavailableValue()
        );
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return now()->addHour();
    }
}
