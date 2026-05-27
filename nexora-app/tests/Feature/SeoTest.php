<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_returns_valid_urls(): void
    {
        Page::query()->create([
            'slug' => 'about',
            'title' => 'О нас',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'active' => true,
        ]);

        Project::query()->create([
            'slug' => 'demo-project',
            'title' => 'Demo Project',
            'type' => 'Сайт',
            'year' => 2026,
            'initial' => 'D',
            'active' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<loc>'.url('/').'</loc>', false);
        $response->assertSee('<loc>'.url('/about').'</loc>', false);
        $response->assertSee('<loc>'.url('/projects/demo-project').'</loc>', false);
    }

    public function test_pricing_page_renders_server_side_seo_meta(): void
    {
        Page::query()->create([
            'slug' => 'pricing',
            'title' => 'Цены — Nexora',
            'description' => 'Тарифы Nexora',
            'seo_title' => 'Цены на разработку сайтов — Nexora',
            'seo_description' => 'Стоимость разработки сайтов от 890 BYN.',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'active' => true,
        ]);

        $response = $this->get('/pricing');

        $response->assertOk();
        $response->assertSee('<title>Цены на разработку сайтов — Nexora</title>', false);
        $response->assertSee('name="description" content="Стоимость разработки сайтов от 890 BYN."', false);
        $response->assertSee('rel="canonical" href="'.url('/pricing').'"', false);
        $response->assertSee('property="og:title" content="Цены на разработку сайтов — Nexora"', false);
    }

    public function test_project_api_returns_project_by_slug(): void
    {
        Project::query()->create([
            'slug' => 'ecotravel',
            'title' => 'Ecotravel',
            'type' => 'Сайт',
            'year' => 2026,
            'initial' => 'E',
            'seo_title' => 'Ecotravel — Nexora',
            'seo_description' => 'Кейс Ecotravel',
            'active' => true,
        ]);

        $response = $this->getJson('/api/projects/ecotravel');

        $response->assertOk();
        $response->assertJsonPath('data.slug', 'ecotravel');
        $response->assertJsonPath('data.seo_title', 'Ecotravel — Nexora');
    }

    public function test_project_page_renders_server_side_seo_by_slug(): void
    {
        Project::query()->create([
            'slug' => 'ecotravel',
            'title' => 'Ecotravel',
            'type' => 'Сайт',
            'year' => 2026,
            'initial' => 'E',
            'seo_title' => 'Ecotravel — кейс Nexora',
            'seo_description' => 'Разработка сайта Ecotravel',
            'active' => true,
        ]);

        $response = $this->get('/projects/ecotravel');

        $response->assertOk();
        $response->assertSee('<title>Ecotravel — кейс Nexora</title>', false);
        $response->assertSee('rel="canonical" href="'.url('/projects/ecotravel').'"', false);
    }
}
