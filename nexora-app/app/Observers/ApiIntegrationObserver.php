<?php

namespace App\Observers;

use App\Models\ApiIntegration;
use App\Services\Integrations\IntegrationManager;

class ApiIntegrationObserver
{
    public function saved(ApiIntegration $integration): void
    {
        app(IntegrationManager::class)->forgetCache($integration->driver);
    }
}
