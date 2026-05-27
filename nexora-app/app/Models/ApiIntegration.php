<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiIntegration extends Model
{
    public const TEST_STATUS_SUCCESS = 'success';

    public const TEST_STATUS_FAILED = 'failed';

    protected $fillable = [
        'slug',
        'driver',
        'name',
        'enabled',
        'credentials',
        'settings',
        'last_tested_at',
        'last_test_status',
        'last_test_message',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'credentials' => 'encrypted:array',
        'settings' => 'array',
        'last_tested_at' => 'datetime',
    ];

    public function credential(string $key, mixed $default = null): mixed
    {
        return data_get($this->credentials, $key, $default);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings, $key, $default);
    }

    public function setCredential(string $key, mixed $value): void
    {
        $credentials = $this->credentials ?? [];
        $credentials[$key] = $value;
        $this->credentials = $credentials;
    }

    public function setSetting(string $key, mixed $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
    }

    public function markTestResult(bool $success, string $message): void
    {
        $this->forceFill([
            'last_tested_at' => now(),
            'last_test_status' => $success ? self::TEST_STATUS_SUCCESS : self::TEST_STATUS_FAILED,
            'last_test_message' => $message,
        ])->saveQuietly();
    }
}
