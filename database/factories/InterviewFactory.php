<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration_minutes' => $this->faker->numberBetween(30, 120),
            'location' => $this->faker->randomElement(['Office', 'Remote', 'Video Call']),
            'meeting_link' => $this->faker->optional(0.3)->url(),
            'created_by' => User::factory(),
        ];
    }
}