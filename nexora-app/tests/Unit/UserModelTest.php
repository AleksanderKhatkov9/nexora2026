<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_belongs_to_role(): void
    {
        $role = UserRole::factory()->admin()->create();
        $user = User::factory()->withRole($role)->create();

        $this->assertTrue($user->userRole->is($role));
    }

    public function test_user_is_admin_when_role_is_admin(): void
    {
        $role = UserRole::factory()->admin()->create();
        $user = User::factory()->withRole($role)->create();

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_is_not_admin_when_role_is_client(): void
    {
        $role = UserRole::factory()->client()->create();
        $user = User::factory()->withRole($role)->create();

        $this->assertFalse($user->isAdmin());
    }
}