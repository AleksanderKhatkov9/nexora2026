<?php

namespace Database\Factories;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserRole>
 */
class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(UserRole::ROLES),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => UserRole::ROLE_ADMIN,
        ]);
    }

    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => UserRole::ROLE_MODERATOR,
        ]);
    }

    public function operator(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => UserRole::ROLE_OPERATOR,
        ]);
    }

    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => UserRole::ROLE_CLIENT,
        ]);
    }
}
