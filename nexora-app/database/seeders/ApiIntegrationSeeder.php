<?php

namespace Database\Seeders;

use App\Models\ApiIntegration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiIntegrationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('integrations.drivers', []) as $driver => $definition) {
            ApiIntegration::query()->updateOrCreate(
                ['driver' => $driver],
                [
                    'slug' => Str::slug($driver),
                    'name' => $definition['name'] ?? $driver,
                    'enabled' => false,
                    'settings' => collect($definition['setting_fields'] ?? [])
                        ->mapWithKeys(fn (array $field, string $key) => array_key_exists('default', $field)
                            ? [$key => $field['default']]
                            : [])
                        ->all(),
                ]
            );
        }
    }
}
