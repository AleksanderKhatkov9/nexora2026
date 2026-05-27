<?php

namespace App\Integrations\Testers;

use App\Exceptions\YandexWebmasterException;
use App\Integrations\Contracts\IntegrationTesterInterface;
use App\Integrations\IntegrationTestResult;
use App\Models\ApiIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class YandexWebmasterTester implements IntegrationTesterInterface
{
    public function test(ApiIntegration $integration): IntegrationTestResult
    {
        $token = $integration->credential('oauth_token');
        $baseUrl = (string) config('yandex.webmaster.api_base');

        if (! filled($token)) {
            return IntegrationTestResult::failed('Укажите OAuth-токен.');
        }

        try {
            $userId = (int) ($this->get($baseUrl, $token, '/user')['user_id'] ?? 0);
            $hostsPayload = $this->get($baseUrl, $token, "/user/{$userId}/hosts");
            $hosts = $hostsPayload['hosts'] ?? [];
            $host = $this->resolveHost($hosts, (string) $integration->setting('site_url', config('app.url')));

            $summary = $this->get($baseUrl, $token, "/user/{$userId}/hosts/{$host['host_id']}/summary");

            return IntegrationTestResult::success(
                'Подключение успешно.',
                [
                    'user_id' => $userId,
                    'host_id' => $host['host_id'],
                    'host_url' => $host['unicode_host_url'] ?? $host['ascii_host_url'] ?? null,
                    'verified' => (bool) ($host['verified'] ?? false),
                    'sqi' => $summary['sqi'] ?? null,
                    'searchable_pages_count' => $summary['searchable_pages_count'] ?? null,
                ]
            );
        } catch (YandexWebmasterException $exception) {
            return IntegrationTestResult::failed($exception->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function get(string $baseUrl, string $token, string $path): array
    {
        $response = Http::withToken($token)
            ->acceptJson()
            ->timeout(20)
            ->get($baseUrl.$path);

        if ($response->failed()) {
            throw YandexWebmasterException::fromResponse($response);
        }

        return $response->json() ?? [];
    }

    /**
     * @param  list<array<string, mixed>>  $hosts
     * @return array<string, mixed>
     */
    private function resolveHost(array $hosts, string $siteUrl): array
    {
        $targetHost = $this->normalizeHost($siteUrl);

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

        throw YandexWebmasterException::hostNotFound($siteUrl);
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
}
