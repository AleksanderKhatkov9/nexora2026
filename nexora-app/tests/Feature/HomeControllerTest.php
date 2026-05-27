<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_page_is_available(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertViewIs('backend.page.index');
    }

    public function test_projects_page_is_available(): void
    {
        $response = $this->get(route('projects'));

        $response->assertOk();
        $response->assertViewIs('projects');
        $response->assertSee('Портфолио', false);
        $response->assertSee('Ecotravel', false);
    }
}
