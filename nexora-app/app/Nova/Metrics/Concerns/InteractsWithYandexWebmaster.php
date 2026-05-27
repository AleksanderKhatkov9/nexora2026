<?php

namespace App\Nova\Metrics\Concerns;

use App\Exceptions\YandexWebmasterException;
use App\Services\YandexWebmaster\YandexWebmasterService;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\PartitionResult;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Metrics\ValueResult;

trait InteractsWithYandexWebmaster
{
    protected function yandexWebmaster(): YandexWebmasterService
    {
        return app(YandexWebmasterService::class);
    }

    protected function rangeDays(NovaRequest $request, int $default = 7): int
    {
        $range = $request->range ?? $default;

        return is_numeric($range) ? (int) $range : $default;
    }

    protected function unavailableValue(string $suffix = 'API не настроен'): ValueResult
    {
        return (new ValueResult)->result(0)->suffix('— '.$suffix);
    }

    protected function unavailableTrend(string $suffix = 'API не настроен'): TrendResult
    {
        return (new TrendResult)->result(0)->suffix('— '.$suffix)->trend([]);
    }

    protected function unavailablePartition(string $suffix = 'API не настроен'): PartitionResult
    {
        return new PartitionResult([
            $suffix => 1,
        ]);
    }

    /**
     * @template T
     *
     * @param  callable(YandexWebmasterService): T  $callback
     * @return T|ValueResult|TrendResult|PartitionResult
     */
    protected function withYandexWebmaster(callable $callback, ValueResult|TrendResult|PartitionResult $fallback)
    {
        $service = $this->yandexWebmaster();

        if (! $service->isEnabled()) {
            return $fallback;
        }

        try {
            return $callback($service);
        } catch (YandexWebmasterException $exception) {
            Log::warning('Yandex Webmaster API error: '.$exception->getMessage());

            if ($fallback instanceof ValueResult) {
                return $this->unavailableValue($exception->getMessage());
            }

            if ($fallback instanceof TrendResult) {
                return $this->unavailableTrend($exception->getMessage());
            }

            return $this->unavailablePartition($exception->getMessage());
        }
    }
}
