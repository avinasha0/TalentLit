<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'application_id' => Application::factory(),
            'candidate_id' => Candidate::factory(),
            'type' => $this->faker->randomElement(['task', 'call', 'email', 'event']),
            'due_at' => $this->faker->optional(0.7)->dateTimeBetween('now', '+7 days'),
            'completed_at' => $this->faker->optional(0.3)->dateTimeBetween('-7 days', 'now'),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->optional(0.8)->paragraphs(2, true),
            'user_id' => User::factory(),
        ];
    }
}
