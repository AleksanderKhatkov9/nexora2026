<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = collect(UserRole::ROLES)
            ->mapWithKeys(function (string $title): array {
                $role = UserRole::query()->firstOrCreate(
                    ['title' => $title],
                    UserRole::factory()->make(['title' => $title])->toArray()
                );

                return [$title => $role];
            });

        $this->seedUser('Admin', 'admin@nexora.loc', $roles[UserRole::ROLE_ADMIN]);
        $this->seedUser('Moderator', 'moderator@nexora.loc', $roles[UserRole::ROLE_MODERATOR]);
        $this->seedUser('Operator', 'operator@nexora.loc', $roles[UserRole::ROLE_OPERATOR]);

        if (! User::query()->where('user_role_id', $roles[UserRole::ROLE_CLIENT]->id)->exists()) {
            User::factory()
                ->count(5)
                ->withRole($roles[UserRole::ROLE_CLIENT])
                ->create();
        }
    }

    private function seedUser(string $name, string $email, UserRole $role): void
    {
        $user = User::factory()
            ->withRole($role)
            ->make([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
            ]);

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $user->name,
                'password' => $user->password,
                'email_verified_at' => $user->email_verified_at,
                'user_role_id' => $user->user_role_id,
                'remember_token' => $user->remember_token,
            ]
        );
    }
}
