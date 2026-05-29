<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use App\Models\UserRole;
use App\Nova\Page as NovaPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tests\TestCase;

class NovaPageResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_resource_fields_resolve_on_index_without_error(): void
    {
        $role = UserRole::query()->create(['title' => UserRole::ROLE_ADMIN]);
        $user = User::factory()->create(['user_role_id' => $role->id]);
        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'active' => true,
            'menu_type' => Page::MENU_TYPE_ROUTE,
        ]);

        $this->actingAs($user);

        $request = NovaRequest::create('/nova-api/pages/filters', 'GET');
        $resource = new NovaPage(Page::first());

        $resource->filterableFields($request);

        $this->addToAssertionCount(1);
    }
}
