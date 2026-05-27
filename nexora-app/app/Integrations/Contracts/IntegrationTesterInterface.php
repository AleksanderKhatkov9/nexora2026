<?php

namespace App\Integrations\Contracts;

use App\Integrations\IntegrationTestResult;
use App\Models\ApiIntegration;

interface IntegrationTesterInterface
{
    public function test(ApiIntegration $integration): IntegrationTestResult;
}
