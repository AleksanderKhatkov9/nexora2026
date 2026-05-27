<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_navigation_api_returns_menu_items(): void
    {
        Page::query()->create([
            'slug' => 'pricing',
            'title' => 'Цены',
            'menu_label' => 'Цены',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'show_in_menu' => true,
            'menu_order' => 1,
            'active' => true,
        ]);

        Page::query()->create([
            'slug' => 'nav-services',
            'title' => 'Услуги',
            'menu_label' => 'Услуги',
            'menu_type' => Page::MENU_TYPE_ANCHOR,
            'menu_hash' => '#services',
            'show_in_menu' => true,
            'menu_order' => 0,
            'active' => true,
        ]);

        $response = $this->getJson('/api/page/navigation');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.label', 'Услуги');
        $response->assertJsonPath('data.0.type', 'anchor');
        $response->assertJsonPath('data.1.route_name', 'pricing');
    }

    public function test_page_api_returns_generic_page_by_slug(): void
    {
        Page::query()->create([
            'slug' => 'about',
            'title' => 'О компании',
            'description' => 'О нас',
            'content' => ['body' => 'Текст страницы'],
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'active' => true,
        ]);

        $response = $this->getJson('/api/page/about');

        $response->assertOk();
        $response->assertJsonPath('data.slug', 'about');
        $response->assertJsonPath('data.content.body', 'Текст страницы');
    }

    public function test_anchor_only_page_is_not_available_as_content_page(): void
    {
        Page::query()->create([
            'slug' => 'nav-contact',
            'title' => 'Контакты',
            'menu_type' => Page::MENU_TYPE_ANCHOR,
            'menu_hash' => '#contact',
            'show_in_menu' => true,
            'active' => true,
        ]);

        $this->getJson('/api/page/nav-contact')->assertNotFound();
    }
}
