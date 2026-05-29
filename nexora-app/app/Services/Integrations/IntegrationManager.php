<?php

namespace App\Services\Integrations;

use App\Integrations\Contracts\IntegrationTesterInterface;
use App\Integrations\IntegrationTestResult;
use App\Integrations\ResolvedIntegration;
use App\Models\ApiIntegration;
use Illuminate\Support\Facades\Cache;

class IntegrationManager
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function drivers(): array
    {
        return config('integrations.drivers', []);
    }

    public function driverDefinition(string $driver): ?array
    {
        return $this->drivers()[$driver] ?? null;
    }

    public function resolve(string $driver): ResolvedIntegration
    {
        $definition = $this->driverDefinition($driver);

        if ($definition === null) {
            return new ResolvedIntegration($driver, false, [], [], 'unknown');
        }

        $integration = ApiIntegration::query()
            ->where('driver', $driver)
            ->first();

        if ($integration !== null && ($integration->enabled || filled($integration->credentials))) {
            return new ResolvedIntegration(
                driver: $driver,
                enabled: (bool) $integration->enabled,
                credentials: $this->filterFilled($integration->credentials ?? []),
                settings: $this->mergeSettings($definition, $integration->settings ?? []),
                source: 'database',
            );
        }

        return $this->resolveFromFallback($driver, $definition);
    }

    public function isEnabled(string $driver): bool
    {
        $resolved = $this->resolve($driver);

        return $resolved->enabled && filled($resolved->credentials);
    }

    public function test(ApiIntegration $integration): IntegrationTestResult
    {
        $testerClass = $this->driverDefinition($integration->driver)['tester'] ?? null;

        if ($testerClass === null || ! is_subclass_of($testerClass, IntegrationTesterInterface::class)) {
            return IntegrationTestResult::failed('Для этого сервиса не настроена проверка подключения.');
        }

        /** @var IntegrationTesterInterface $tester */
        $tester = app($testerClass);
        $result = $tester->test($integration);

        $integration->markTestResult($result->success, $result->message);
        $this->forgetCache($integration->driver);

        return $result;
    }

    public function forgetCache(string $driver): void
    {
        $keys = match ($driver) {
            'yandex_webmaster' => [
                'yandex.webmaster.user-id',
                'yandex.webmaster.host-id',
            ],
            default => [],
        };

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Сброс кеша search-history/summary по известным паттернам — через tags недоступно,
        // поэтому при сохранении интеграции очищаем весь cache driver (file) для dev/MVP.
        if ($driver === 'yandex_webmaster') {
            for ($days = 7; $days <= 90; $days += 7) {
                Cache::forget("yandex.webmaster.search-history.{$days}");
            }

            Cache::forget('yandex.webmaster.summary');

            for ($days = 7; $days <= 90; $days += 7) {
                for ($limit = 8; $limit <= 10; $limit++) {
                    Cache::forget("yandex.webmaster.popular-queries.{$days}.{$limit}");
                }
            }
        }
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private function resolveFromFallback(string $driver, array $definition): ResolvedIntegration
    {
        $fallback = $definition['fallback'] ?? [];
        $enabled = filter_var($fallback['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $credentials = $this->filterFilled($fallback['credentials'] ?? []);
        $settings = $this->filterFilled($fallback['settings'] ?? []);

        return new ResolvedIntegration(
            driver: $driver,
            enabled: $enabled,
            credentials: $credentials,
            settings: $this->mergeSettings($definition, $settings),
            source: 'config',
        );
    }

    /**
     * @param  array<string, mixed>  $definition
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    private function mergeSettings(array $definition, array $settings): array
    {
        $defaults = [];

        foreach ($definition['setting_fields'] ?? [] as $key => $field) {
            if (array_key_exists('default', $field)) {
                $defaults[$key] = $field['default'];
            }
        }

        return array_merge($defaults, $this->filterFilled($settings));
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private function filterFilled(array $values): array
    {
        return collect($values)
            ->filter(fn ($value) => filled($value))
            ->all();
    }
}
