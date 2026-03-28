<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'title' => fake()->sentence(2),
            'description' => fake()->sentence(4),
            'status' => fake()->randomElement(['pending', 'in_progress', 'done']),
            'due_date' => fake()->dateTimeBetween('now', '+30 day'),
            'user_id' => User::inRandomOrder()->first()?->id,
            'assignee_id' => User::inRandomOrder()->first()?->id
        ];
    }
}
