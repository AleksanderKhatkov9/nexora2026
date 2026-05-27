<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewOrders;
use App\Nova\Metrics\OrdersByChannel;
use App\Nova\Metrics\OrdersByStatus;
use App\Nova\Metrics\OrdersCompletionProgress;
use App\Nova\Metrics\OrdersTrend;
use App\Nova\Metrics\OrdersTrendByMonth;
use App\Nova\Metrics\TotalOrders;
use Laravel\Nova\Dashboard;

class OrdersAnalytics extends Dashboard
{
    public function label(): string
    {
        return 'Аналитика заявок';
    }

    public function uriKey(): string
    {
        return 'orders-analytics';
    }

    public function cards(): array
    {
        return [
            (new TotalOrders)->width('1/3'),
            (new NewOrders)->width('1/3'),
            (new OrdersCompletionProgress)->width('1/3'),

            (new OrdersTrend)->width('full'),

            (new OrdersTrendByMonth)->width('2/3'),
            (new OrdersByStatus)->width('1/3'),

            (new OrdersByChannel)->width('1/2'),
        ];
    }
}
