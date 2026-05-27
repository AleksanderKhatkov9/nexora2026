<?php

namespace App\Nova\Metrics\YandexWebmaster;

use App\Nova\Metrics\Concerns\InteractsWithYandexWebmaster;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class YandexTopSearchQueries extends Partition
{
    use InteractsWithYandexWebmaster;

    public function name(): string
    {
        return 'Топ запросов (30 дней)';
    }

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->withYandexWebmaster(
            function ($service) {
                $queries = $service->getPopularQueries(30, 8);
                $segments = [];

                foreach ($queries as $query) {
                    $label = (string) ($query['query_text'] ?? '—');
                    $segments[$label] = (int) round((float) ($query['indicators']['TOTAL_CLICKS'] ?? 0));
                }

                return (new PartitionResult($segments))
                    ->label(fn (string $value) => mb_strlen($value) > 40 ? mb_substr($value, 0, 37).'…' : $value);
            },
            $this->unavailablePartition()
        );
    }

    public function cacheFor(): ?DateTimeInterface
    {
        return now()->addMinutes(30);
    }
}
