<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ApiIntegrationSeeder::class,
            TagsSeeder::class,
            ProjectSeeder::class,
            PageSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
