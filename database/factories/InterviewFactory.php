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
            'tenant_id' => \App\Models\Tenant::factory(),
            'candidate_id' => \App\Models\Candidate::factory(),
            'job_id' => \App\Models\JobOpening::factory(),
            'application_id' => Application::factory(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration_minutes' => $this->faker->numberBetween(30, 120),
            'mode' => $this->faker->randomElement(['onsite', 'remote', 'phone']),
            'location' => $this->faker->randomElement(['Office', 'Remote', 'Video Call']),
            'meeting_link' => $this->faker->optional(0.3)->url(),
            'created_by' => User::factory(),
            'notes' => $this->faker->optional(0.5)->sentence(),
            'status' => $this->faker->randomElement(['scheduled', 'completed', 'canceled']),
        ];
    }
}