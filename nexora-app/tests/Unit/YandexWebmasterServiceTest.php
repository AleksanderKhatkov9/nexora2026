<?php

namespace Tests\Unit;

use App\Models\ApiIntegration;
use App\Services\Integrations\IntegrationManager;
use App\Services\YandexWebmaster\YandexWebmasterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YandexWebmasterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        $this->seed(\Database\Seeders\ApiIntegrationSeeder::class);

        Config::set('yandex.webmaster.api_base', 'https://api.webmaster.yandex.net/v4');
    }

    public function test_it_resolves_host_and_fetches_summary_from_nova_integration(): void
    {
        $this->configureIntegration([
            'oauth_token' => 'test-token',
            'site_url' => 'https://nexora.by',
        ]);

        Http::fake([
            'https://api.webmaster.yandex.net/v4/user' => Http::response(['user_id' => 42]),
            'https://api.webmaster.yandex.net/v4/user/42/hosts' => Http::response([
                'hosts' => [
                    [
                        'host_id' => 'https:nexora.by:443',
                        'ascii_host_url' => 'https://nexora.by/',
                        'unicode_host_url' => 'https://nexora.by/',
                        'verified' => true,
                    ],
                ],
            ]),
            'https://api.webmaster.yandex.net/v4/user/42/hosts/https:nexora.by:443/summary' => Http::response([
                'sqi' => 120,
                'searchable_pages_count' => 15,
                'excluded_pages_count' => 2,
                'site_problems' => [],
            ]),
        ]);

        $summary = app(YandexWebmasterService::class)->getSummary();

        $this->assertSame(120, $summary['sqi']);
        $this->assertSame(15, $summary['searchable_pages_count']);
    }

    public function test_it_fetches_search_history_with_repeated_query_indicators(): void
    {
        $this->configureIntegration([
            'oauth_token' => 'test-token',
            'host_id' => 'https:nexora.by:443',
        ]);

        Http::fake([
            'https://api.webmaster.yandex.net/v4/user' => Http::response(['user_id' => 42]),
            'https://api.webmaster.yandex.net/v4/user/42/hosts/https:nexora.by:443/search-queries/all/history*' => Http::response([
                'indicators' => [
                    'TOTAL_CLICKS' => [
                        ['date' => '2026-05-25T00:00:00.000+03:00', 'value' => 3],
                        ['date' => '2026-05-26T00:00:00.000+03:00', 'value' => 5],
                    ],
                    'TOTAL_SHOWS' => [
                        ['date' => '2026-05-25T00:00:00.000+03:00', 'value' => 30],
                        ['date' => '2026-05-26T00:00:00.000+03:00', 'value' => 40],
                    ],
                ],
            ]),
        ]);

        $service = app(YandexWebmasterService::class);
        $history = $service->getSearchHistory(7);

        $this->assertSame(8, $service->sumIndicator($history, 'TOTAL_CLICKS'));
        $this->assertSame(70, $service->sumIndicator($history, 'TOTAL_SHOWS'));
    }

    public function test_connection_status_reports_missing_token(): void
    {
        $this->configureIntegration([
            'oauth_token' => null,
        ]);

        $status = app(YandexWebmasterService::class)->getConnectionStatus();

        $this->assertFalse($status['configured']);
        $this->assertStringContainsString('OAuth-токен', (string) $status['message']);
    }

    public function test_integration_manager_tests_yandex_connection(): void
    {
        $this->configureIntegration([
            'oauth_token' => 'test-token',
            'site_url' => 'https://nexora.by',
        ]);

        Http::fake([
            'https://api.webmaster.yandex.net/v4/user' => Http::response(['user_id' => 42]),
            'https://api.webmaster.yandex.net/v4/user/42/hosts' => Http::response([
                'hosts' => [
                    [
                        'host_id' => 'https:nexora.by:443',
                        'ascii_host_url' => 'https://nexora.by/',
                        'verified' => true,
                    ],
                ],
            ]),
            'https://api.webmaster.yandex.net/v4/user/42/hosts/https:nexora.by:443/summary' => Http::response([
                'sqi' => 50,
                'searchable_pages_count' => 10,
                'excluded_pages_count' => 0,
                'site_problems' => [],
            ]),
        ]);

        $integration = ApiIntegration::query()->where('driver', 'yandex_webmaster')->firstOrFail();
        $result = app(IntegrationManager::class)->test($integration);

        $this->assertTrue($result->success);
        $this->assertSame(ApiIntegration::TEST_STATUS_SUCCESS, $integration->fresh()->last_test_status);
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    private function configureIntegration(array $settings): void
    {
        $integration = ApiIntegration::query()->where('driver', 'yandex_webmaster')->firstOrFail();

        $integration->forceFill([
            'enabled' => true,
            'credentials' => filled($settings['oauth_token'] ?? null)
                ? ['oauth_token' => $settings['oauth_token']]
                : [],
            'settings' => collect($settings)->except('oauth_token')->all(),
        ])->save();

        $this->app->forgetInstance(YandexWebmasterService::class);
        $this->app->forgetInstance(\App\Services\YandexWebmaster\YandexWebmasterClient::class);
    }
}
