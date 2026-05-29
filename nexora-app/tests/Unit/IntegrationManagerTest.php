<?php

namespace Tests\Unit;

use App\Models\ApiIntegration;
use App\Services\Integrations\IntegrationManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_available_drivers(): void
    {
        $this->seed(\Database\Seeders\ApiIntegrationSeeder::class);

        $this->assertDatabaseHas('api_integrations', [
            'driver' => 'yandex_webmaster',
            'name' => 'Яндекс.Вебмастер',
        ]);
    }

    public function test_it_prefers_database_credentials_over_config_fallback(): void
    {
        $this->seed(\Database\Seeders\ApiIntegrationSeeder::class);

        $integration = ApiIntegration::query()->where('driver', 'yandex_webmaster')->firstOrFail();
        $integration->forceFill([
            'enabled' => true,
            'credentials' => ['oauth_token' => 'db-token'],
            'settings' => ['site_url' => 'https://nexora.by'],
        ])->save();

        $resolved = app(IntegrationManager::class)->resolve('yandex_webmaster');

        $this->assertSame('database', $resolved->source);
        $this->assertSame('db-token', $resolved->credential('oauth_token'));
    }

    public function test_it_resolves_config_fallback_when_database_credentials_are_empty(): void
    {
        config()->set('integrations.drivers.yandex_webmaster.fallback', [
            'enabled' => true,
            'credentials' => [
                'oauth_token' => 'config-token',
            ],
            'settings' => [
                'site_url' => 'https://nexora.by',
            ],
        ]);

        $resolved = app(IntegrationManager::class)->resolve('yandex_webmaster');

        $this->assertSame('config', $resolved->source);
        $this->assertTrue($resolved->enabled);
        $this->assertSame('config-token', $resolved->credential('oauth_token'));
    }
}
