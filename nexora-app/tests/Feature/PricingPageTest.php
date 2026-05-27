<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_pricing_page_route_is_available(): void
    {
        $response = $this->get('/pricing');

        $response->assertOk();
        $response->assertViewIs('index');
    }

    public function test_pricing_api_returns_page_data(): void
    {
        Page::query()->create([
            'slug' => 'pricing',
            'title' => 'Цены — Nexora',
            'description' => 'Описание тарифов',
            'content' => [
                'intro' => ['eyebrow' => 'Стоимость', 'lead' => 'Текст'],
                'plans' => [
                    ['name' => 'Старт', 'price' => '890', 'currency' => 'BYN', 'features' => []],
                ],
            ],
            'active' => true,
        ]);

        $response = $this->getJson('/api/page/pricing');

        $response->assertOk();
        $response->assertJsonPath('data.slug', 'pricing');
        $response->assertJsonPath('data.content.plans.0.name', 'Старт');
    }
}
