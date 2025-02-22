<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Number;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class JobVacancyFactory extends Factory
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
            'active' => rand(0,1),
            'name' => fake()->name(),
            'position' => '["s'.rand(0,23).'","m'.rand(0,90).'"]',
            'description' => Str::random(255),
            'requirements' => Str::random(255),
            'payment' => rand(0,1000000),
            'created_by' => rand(0,100),
            'address' => rand(1,8).''.rand(1,8).''.rand(1,8)
                                .''.rand(1,8).','.Str::random(10).','.Str::random(10),
            'company_desc' => Str::random(255),
            'created_at' => now(),
            'updated_at'=> now(),
            'is_active' => rand(0,1),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
