<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'nexora-zapusk-2026',
                'title' => 'Nexora запускает обновлённый сайт частного разработчика',
                'kind' => BlogPost::KIND_NEWS,
                'excerpt' => 'Представили новый сайт с портфолио, тарифами и улучшенной SEO-структурой.',
                'content' => "Я обновил публичную часть Nexora: теперь клиенты могут смотреть кейсы, изучать тарифы и оставлять заявку в несколько кликов.\n\nСайт собран на Laravel + Vue и управляется через Nova — контент можно менять без правок в коде.",
                'author' => 'Nexora',
                'published_at' => Carbon::parse('2026-05-20'),
            ],
            [
                'slug' => 'novye-tarify-razrabotki',
                'title' => 'Обновили тарифы на разработку сайтов',
                'kind' => BlogPost::KIND_NEWS,
                'excerpt' => 'Актуальные пакеты для лендингов, корпоративных сайтов и интернет-магазинов.',
                'content' => "В разделе «Цены» опубликованы три тарифа с прозрачным составом работ.\n\nДля каждого проекта по-прежнему доступна индивидуальная смета после брифа.",
                'author' => 'Nexora',
                'published_at' => Carbon::parse('2026-05-10'),
            ],
            [
                'slug' => 'kak-vybrat-podryadchika-na-sajt',
                'title' => 'Как выбрать подрядчика на создание сайта в 2026 году',
                'kind' => BlogPost::KIND_ARTICLE,
                'excerpt' => 'Чеклист для бизнеса: технологии, SEO, поддержка и прозрачность процессов.',
                'content' => "Перед стартом проекта проверьте портфолио, стек технологий, наличие CMS или админки, SEO-базу и условия сопровождения.\n\nХороший подрядчик заранее проговаривает сроки, этапы и передачу исходников.",
                'author' => 'Nexora',
                'published_at' => Carbon::parse('2026-05-15'),
            ],
            [
                'slug' => 'seo-dlya-sajta-vizitki',
                'title' => 'SEO для сайта-визитки: с чего начать',
                'kind' => BlogPost::KIND_ARTICLE,
                'excerpt' => 'Server-side meta, sitemap, структура страниц и контент под локальные запросы.',
                'content' => "Минимальный набор для старта: уникальные title/description, canonical, sitemap.xml, понятные URL и страницы под ключевые услуги.\n\nДальше — регулярный контент: кейсы, статьи и новости компании.",
                'author' => 'Nexora',
                'published_at' => Carbon::parse('2026-05-05'),
            ],
        ];

        foreach ($posts as $data) {
            BlogPost::query()->updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'active' => true,
                    'seo_title' => $data['title'].' — Nexora',
                    'seo_description' => $data['excerpt'],
                ])
            );
        }
    }
}
