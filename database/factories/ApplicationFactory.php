<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'job_opening_id' => JobOpening::factory(),
            'candidate_id' => Candidate::factory(),
            'status' => $this->faker->randomElement(['active', 'withdrawn', 'hired', 'rejected']),
            'current_stage_id' => JobStage::factory(),
            'applied_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }
}
