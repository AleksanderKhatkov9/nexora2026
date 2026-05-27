<?php

namespace App\Nova\Metrics\YandexWebmaster;

use App\Nova\Metrics\Concerns\InteractsWithYandexWebmaster;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class YandexSearchClicksTrend extends Trend
{
    use InteractsWithYandexWebmaster;

    public function name(): string
    {
        return 'Динамика переходов из Яндекса';
    }

    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->withYandexWebmaster(
            function ($service) use ($request) {
                $days = $this->rangeDays($request);
                $history = $service->getSearchHistory($days);
                $trend = $service->dailyTrend($history, 'TOTAL_CLICKS');

                return (new TrendResult)
                    ->showSumValue()
                    ->suffix('кликов')
                    ->trend($trend);
            },
            $this->unavailableTrend()
        );
    }

    public function ranges(): array
    {
        return [
            7 => '7 дней',
            14 => '14 дней',
            30 => '30 дней',
            90 => '90 дней',
        ];
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return now()->addMinutes(30);
    }
}
