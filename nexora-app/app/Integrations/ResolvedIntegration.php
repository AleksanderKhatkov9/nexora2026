<?php

namespace App\Integrations;

class ResolvedIntegration
{
    /**
     * @param  array<string, mixed>  $credentials
     * @param  array<string, mixed>  $settings
     */
    public function __construct(
        public readonly string $driver,
        public readonly bool $enabled,
        public readonly array $credentials,
        public readonly array $settings,
        public readonly string $source,
    ) {}

    public function credential(string $key, mixed $default = null): mixed
    {
        return data_get($this->credentials, $key, $default);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings, $key, $default);
    }

    public function isConfigured(): bool
    {
        return $this->enabled && filled($this->credentials);
    }
}
