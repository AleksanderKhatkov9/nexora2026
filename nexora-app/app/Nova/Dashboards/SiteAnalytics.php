<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\YandexWebmaster\YandexSearchablePages;
use App\Nova\Metrics\YandexWebmaster\YandexSearchClicks;
use App\Nova\Metrics\YandexWebmaster\YandexSearchClicksTrend;
use App\Nova\Metrics\YandexWebmaster\YandexSearchImpressions;
use App\Nova\Metrics\YandexWebmaster\YandexSiteQualityIndex;
use App\Nova\Metrics\YandexWebmaster\YandexTopSearchQueries;
use Laravel\Nova\Dashboard;

class SiteAnalytics extends Dashboard
{
    public function label(): string
    {
        return 'Посещаемость (Яндекс)';
    }

    public function uriKey(): string
    {
        return 'site-analytics';
    }

    public function cards(): array
    {
        return [
            (new YandexSearchClicks)->width('1/4'),
            (new YandexSearchImpressions)->width('1/4'),
            (new YandexSearchablePages)->width('1/4'),
            (new YandexSiteQualityIndex)->width('1/4'),

            (new YandexSearchClicksTrend)->width('full'),

            (new YandexTopSearchQueries)->width('full'),
        ];
    }
}
