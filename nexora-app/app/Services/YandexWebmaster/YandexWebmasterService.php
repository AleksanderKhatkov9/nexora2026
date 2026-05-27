<?php

namespace App\Services\YandexWebmaster;

use App\Exceptions\YandexWebmasterException;
use App\Integrations\ResolvedIntegration;
use App\Services\Integrations\IntegrationManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class YandexWebmasterService
{
    private const DRIVER = 'yandex_webmaster';

    public function __construct(
        private readonly YandexWebmasterClient $client,
        private readonly IntegrationManager $integrations,
    ) {}

    public function isEnabled(): bool
    {
        $config = $this->integrationConfig();

        return $config->enabled && filled($config->credential('oauth_token'));
    }

    /**
     * @return array{
     *     configured: bool,
     *     enabled: bool,
     *     user_id: int|null,
     *     host_id: string|null,
     *     host_url: string|null,
     *     verified: bool|null,
     *     message: string|null
     * }
     */
    public function getConnectionStatus(): array
    {
        $config = $this->integrationConfig();

        if (! $config->enabled) {
            return [
                'configured' => false,
                'enabled' => false,
                'user_id' => null,
                'host_id' => null,
                'host_url' => null,
                'verified' => null,
                'message' => 'Интеграция отключена. Включите её в Nova → API-интеграции → Яндекс.Вебмастер.',
            ];
        }

        if (! filled($config->credential('oauth_token'))) {
            return [
                'configured' => false,
                'enabled' => true,
                'user_id' => null,
                'host_id' => null,
                'host_url' => null,
                'verified' => null,
                'message' => 'Укажите OAuth-токен в Nova → API-интеграции или в .env (YANDEX_WEBMASTER_OAUTH_TOKEN).',
            ];
        }

        try {
            $userId = $this->userId();
            $host = $this->resolveHost();

            return [
                'configured' => true,
                'enabled' => true,
                'user_id' => $userId,
                'host_id' => $host['host_id'],
                'host_url' => $host['unicode_host_url'] ?? $host['ascii_host_url'] ?? null,
                'verified' => (bool) ($host['verified'] ?? false),
                'message' => null,
            ];
        } catch (YandexWebmasterException $exception) {
            return [
                'configured' => true,
                'enabled' => true,
                'user_id' => null,
                'host_id' => $config->setting('host_id'),
                'host_url' => $config->setting('site_url'),
                'verified' => null,
                'message' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{
     *     sqi: int,
     *     searchable_pages_count: int,
     *     excluded_pages_count: int,
     *     site_problems: array<string, int>
     * }
     */
    public function getSummary(): array
    {
        return $this->remember('summary', function () {
            $context = $this->context();

            return $this->client->get("/user/{$context['user_id']}/hosts/{$context['host_id']}/summary");
        });
    }

    /**
     * @return array<string, array<int, array{date: string, value: float}>>
     */
    public function getSearchHistory(int $days): array
    {
        return $this->remember("search-history.{$days}", function () use ($days) {
            $context = $this->context();
            [$dateFrom, $dateTo] = $this->dateRange($days);

            $payload = $this->client->get(
                "/user/{$context['user_id']}/hosts/{$context['host_id']}/search-queries/all/history",
                [
                    'query_indicator' => ['TOTAL_CLICKS', 'TOTAL_SHOWS'],
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                ]
            );

            return $payload['indicators'] ?? [];
        });
    }

    /**
     * @return list<array{query_id: string, query_text: string, indicators: array<string, float>}>
     */
    public function getPopularQueries(int $days, int $limit = 10): array
    {
        return $this->remember("popular-queries.{$days}.{$limit}", function () use ($days, $limit) {
            $context = $this->context();
            [$dateFrom, $dateTo] = $this->dateRange($days);

            $payload = $this->client->get(
                "/user/{$context['user_id']}/hosts/{$context['host_id']}/search-queries/popular",
                [
                    'order_by' => 'TOTAL_CLICKS',
                    'query_indicator' => ['TOTAL_CLICKS', 'TOTAL_SHOWS'],
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'limit' => min($limit, 500),
                ]
            );

            return array_slice($payload['queries'] ?? [], 0, $limit);
        });
    }

    public function sumIndicator(array $indicators, string $indicator): int
    {
        $points = $indicators[$indicator] ?? [];

        return (int) round(collect($points)->sum(fn (array $point) => (float) ($point['value'] ?? 0)));
    }

    /**
     * @return array<string, int>
     */
    public function dailyTrend(array $indicators, string $indicator): array
    {
        $points = $indicators[$indicator] ?? [];
        $trend = [];

        foreach ($points as $point) {
            $date = Carbon::parse($point['date'])->format('F j, Y');
            $trend[$date] = (int) round((float) ($point['value'] ?? 0));
        }

        return $trend;
    }

    private function integrationConfig(): ResolvedIntegration
    {
        return $this->integrations->resolve(self::DRIVER);
    }

    /**
     * @return array{user_id: int, host_id: string}
     */
    private function context(): array
    {
        return [
            'user_id' => $this->userId(),
            'host_id' => $this->hostId(),
        ];
    }

    private function userId(): int
    {
        $ttl = $this->cacheTtl();

        return (int) Cache::remember('yandex.webmaster.user-id', $ttl, function () {
            $payload = $this->client->get('/user');

            return (int) ($payload['user_id'] ?? 0);
        });
    }

    private function hostId(): string
    {
        $configuredHostId = $this->integrationConfig()->setting('host_id');

        if (filled($configuredHostId)) {
            return (string) $configuredHostId;
        }

        $ttl = $this->cacheTtl();

        return (string) Cache::remember('yandex.webmaster.host-id', $ttl, function () {
            return (string) ($this->resolveHost()['host_id'] ?? '');
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveHost(): array
    {
        $userId = $this->userId();
        $payload = $this->client->get("/user/{$userId}/hosts");
        $hosts = $payload['hosts'] ?? [];
        $targetHost = $this->normalizeHost((string) $this->integrationConfig()->setting('site_url', config('app.url')));

        foreach ($hosts as $host) {
            $ascii = $this->normalizeHost((string) ($host['ascii_host_url'] ?? ''));
            $unicode = $this->normalizeHost((string) ($host['unicode_host_url'] ?? ''));

            if ($targetHost !== '' && ($ascii === $targetHost || $unicode === $targetHost || Str::contains($ascii, $targetHost) || Str::contains($unicode, $targetHost))) {
                return $host;
            }
        }

        if (count($hosts) === 1) {
            return $hosts[0];
        }

        throw YandexWebmasterException::hostNotFound((string) $this->integrationConfig()->setting('site_url'));
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function dateRange(int $days): array
    {
        $dateTo = Carbon::now()->startOfDay();
        $dateFrom = $dateTo->copy()->subDays(max($days - 1, 0));

        return [
            $dateFrom->toIso8601String(),
            $dateTo->toIso8601String(),
        ];
    }

    private function normalizeHost(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            $host = preg_replace('#^https?://#', '', $url) ?? $url;
            $host = explode('/', $host)[0];
        }

        return strtolower(preg_replace('/^www\./', '', $host) ?? $host);
    }

    /**
     * @param  callable(): array<string, mixed>  $callback
     * @return array<string, mixed>
     */
    private function remember(string $key, callable $callback): array
    {
        /** @var array<string, mixed> $result */
        $result = Cache::remember("yandex.webmaster.{$key}", $this->cacheTtl(), $callback);

        return $result;
    }

    private function cacheTtl(): int
    {
        return max((int) $this->integrationConfig()->setting('cache_ttl', 3600), 60);
    }
}
