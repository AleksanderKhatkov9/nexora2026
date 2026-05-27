<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\ActiveProjects;
use App\Nova\Metrics\NewOrders;
use App\Nova\Metrics\NewUsers;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function cards(): array
    {
        return [
            new NewOrders,
            new ActiveProjects,
            new NewUsers,
        ];
    }
}
