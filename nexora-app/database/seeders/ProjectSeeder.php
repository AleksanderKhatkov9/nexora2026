<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tags;
use App\Support\PortfolioData;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PortfolioData::projects() as $data) {
            $project = Project::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'type' => $data['type'],
                    'year' => $data['year'],
                    'initial' => $data['initial'],
                    'active' => true,
                ]
            );

            $tag = Tags::query()->where('slug', $data['tag'])->first();

            if ($tag) {
                $project->tags()->sync([$tag->id]);
            }
        }
    }
}
