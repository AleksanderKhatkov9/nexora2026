<?php

namespace App\Services\YandexWebmaster;

use App\Exceptions\YandexWebmasterException;
use App\Services\Integrations\IntegrationManager;
use Illuminate\Support\Facades\Http;

class YandexWebmasterClient
{
    private const DRIVER = 'yandex_webmaster';

    public function __construct(
        private readonly IntegrationManager $integrations,
        private readonly string $baseUrl,
    ) {}

    public function isConfigured(): bool
    {
        return filled($this->token());
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        if (! $this->isConfigured()) {
            throw YandexWebmasterException::notConfigured();
        }

        $response = Http::withToken((string) $this->token())
            ->acceptJson()
            ->timeout(20)
            ->get($this->buildUrl($path, $query));

        if ($response->failed()) {
            throw YandexWebmasterException::fromResponse($response);
        }

        return $response->json() ?? [];
    }

    /**
     * @param  array<string, mixed>  $query
     */
    private function buildUrl(string $path, array $query): string
    {
        if ($query === []) {
            return $this->baseUrl.$path;
        }

        $parts = [];

        foreach ($query as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $parts[] = rawurlencode((string) $key).'='.rawurlencode((string) $item);
                }

                continue;
            }

            $parts[] = rawurlencode((string) $key).'='.rawurlencode((string) $value);
        }

        return $this->baseUrl.$path.'?'.implode('&', $parts);
    }

    private function token(): ?string
    {
        $token = $this->integrations->resolve(self::DRIVER)->credential('oauth_token');

        return filled($token) ? (string) $token : null;
    }
}
