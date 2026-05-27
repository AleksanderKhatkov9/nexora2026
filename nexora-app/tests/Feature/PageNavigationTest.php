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

    public function test_footer_api_returns_grouped_footer_items(): void
    {
        Page::query()->create([
            'slug' => 'about',
            'title' => 'О компании',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'show_in_footer' => true,
            'footer_group' => Page::FOOTER_GROUP_SECTIONS,
            'footer_order' => 10,
            'footer_label' => 'О нас',
            'active' => true,
        ]);

        Page::query()->create([
            'slug' => 'pricing',
            'title' => 'Цены',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'show_in_footer' => true,
            'footer_group' => Page::FOOTER_GROUP_SERVICES,
            'footer_order' => 10,
            'footer_label' => 'Стоимость создания сайта',
            'active' => true,
        ]);

        Page::query()->create([
            'slug' => 'privacy',
            'title' => 'Политика конфиденциальности',
            'menu_type' => Page::MENU_TYPE_ROUTE,
            'show_in_footer' => true,
            'footer_group' => Page::FOOTER_GROUP_LEGAL,
            'footer_order' => 10,
            'footer_label' => 'Политика в отношении обработки персональных данных',
            'active' => true,
        ]);

        $response = $this->getJson('/api/page/footer');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.label', 'О нас');
        $response->assertJsonPath('data.0.footer_group', 'sections');
        $response->assertJsonPath('data.1.label', 'Стоимость создания сайта');
        $response->assertJsonPath('data.1.footer_group', 'services');
        $response->assertJsonPath('data.2.route_name', 'page');
    }
}
