<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('index');
        $response->assertSee('id="app"', false);
    }

    public function test_projects_page_is_available(): void
    {
        $response = $this->get(route('projects'));

        $response->assertOk();
        $response->assertViewIs('index');
        $response->assertSee('data-projects-url', false);
    }
}
