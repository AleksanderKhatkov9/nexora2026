<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    public function test_not_found_route_renders_custom_404_page(): void
    {
        $response = $this->get('/this/path/does/not/exist');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Страница не найдена');
        $response->assertSee('На главную');
    }

    public function test_error_views_compile(): void
    {
        $this->assertNotEmpty(view('errors.404')->render());
        $this->assertNotEmpty(view('errors.500')->render());
    }
}
