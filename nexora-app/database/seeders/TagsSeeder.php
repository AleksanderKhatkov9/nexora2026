<?php

namespace Database\Seeders;

use App\Models\Tags;
use App\Support\PortfolioData;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PortfolioData::tags() as $tag) {
            if ($tag['slug'] === 'all') {
                continue;
            }

            Tags::query()->updateOrCreate(
                ['slug' => $tag['slug']],
                ['name' => $tag['name']]
            );
        }
    }
}
