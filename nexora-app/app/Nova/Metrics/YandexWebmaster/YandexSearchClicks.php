<?php

namespace App\Nova\Metrics\YandexWebmaster;

use App\Nova\Metrics\Concerns\InteractsWithYandexWebmaster;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class YandexSearchClicks extends Value
{
    use InteractsWithYandexWebmaster;

    public function name(): string
    {
        return 'Переходы из Яндекса';
    }

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->withYandexWebmaster(
            function ($service) use ($request) {
                $days = $this->rangeDays($request);
                $history = $service->getSearchHistory($days);

                return (new ValueResult)
                    ->result($service->sumIndicator($history, 'TOTAL_CLICKS'))
                    ->suffix('кликов');
            },
            $this->unavailableValue()
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
