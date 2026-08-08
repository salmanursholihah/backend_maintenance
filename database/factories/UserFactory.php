<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'name'      => fake('id_ID')->name(),
            'email'     => fake()->unique()->safeEmail(),
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'phone'     => fake('id_ID')->phoneNumber(),
            'address'   => fake('id_ID')->address(),
            'is_active' => true,
            'photo'     => null,
        ];
    }
 
    // php artisan tinker → User::factory()->admin()->create()
    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
 
    // php artisan tinker → User::factory()->technician()->create()
    public function technician(): static
    {
        return $this->state(['role' => 'technician']);
    }
 
    // php artisan tinker → User::factory()->customer()->create()
    public function customer(): static
    {
        return $this->state(['role' => 'customer']);
    }
 
    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
