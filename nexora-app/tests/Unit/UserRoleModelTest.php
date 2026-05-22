<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_has_many_users(): void
    {
        $role = UserRole::factory()->admin()->create();

        User::factory()
            ->count(3)
            ->withRole($role)
            ->create();

        $this->assertCount(3, $role->users);
    }

    public function test_admin_roles_are_defined(): void
    {
        $this->assertContains(UserRole::ROLE_ADMIN, UserRole::ADMINS);
        $this->assertContains(UserRole::ROLE_MODERATOR, UserRole::ADMINS);
        $this->assertContains(UserRole::ROLE_OPERATOR, UserRole::ADMINS);
        $this->assertNotContains(UserRole::ROLE_CLIENT, UserRole::ADMINS);
    }
}