<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'start_date'  => $this->faker->dateTimeBetween('-3 months', 'now'),
            'deadline'    => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }
}
