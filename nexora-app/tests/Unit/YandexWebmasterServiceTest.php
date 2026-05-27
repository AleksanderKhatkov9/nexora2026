<?php

namespace Tests\Unit;

use App\Services\YandexWebmaster\YandexWebmasterClient;
use App\Services\YandexWebmaster\YandexWebmasterService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class YandexWebmasterServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Config::set('yandex.webmaster.enabled', true);
        Config::set('yandex.webmaster.oauth_token', 'test-token');
        Config::set('yandex.webmaster.site_url', 'https://nexora.by');
        Config::set('yandex.webmaster.host_id', null);
        Config::set('yandex.webmaster.cache_ttl', 60);
    }

    public function test_it_resolves_host_and_fetches_summary(): void
    {
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

        $service = app(YandexWebmasterService::class);
        $summary = $service->getSummary();

        $this->assertSame(120, $summary['sqi']);
        $this->assertSame(15, $summary['searchable_pages_count']);
    }

    public function test_it_fetches_search_history_with_repeated_query_indicators(): void
    {
        Config::set('yandex.webmaster.host_id', 'https:nexora.by:443');

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

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'query_indicator=TOTAL_CLICKS')
                && str_contains($request->url(), 'query_indicator=TOTAL_SHOWS');
        });
    }

    public function test_connection_status_reports_missing_token(): void
    {
        Config::set('yandex.webmaster.oauth_token', null);

        $service = new YandexWebmasterService(
            new YandexWebmasterClient(null, config('yandex.webmaster.api_base'))
        );

        $status = $service->getConnectionStatus();

        $this->assertFalse($status['configured']);
        $this->assertStringContainsString('YANDEX_WEBMASTER_OAUTH_TOKEN', (string) $status['message']);
    }
}
