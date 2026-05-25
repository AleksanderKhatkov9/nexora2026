<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Главная',
                'description' => 'Главная страница сайта Nexora',
                'active' => true,
            ],
            [
                'slug' => 'projects',
                'title' => 'Проекты',
                'description' => 'Портфолио выполненных проектов',
                'active' => true,
            ],
            [
                'slug' => 'contacts',
                'title' => 'Контакты',
                'description' => 'Контактная информация',
                'active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
