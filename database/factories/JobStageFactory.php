<?php

namespace Database\Factories;

use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobStage>
 */
class JobStageFactory extends Factory
{
    protected $model = JobStage::class;

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
            'name' => $this->faker->randomElement(['Sourced', 'Screen', 'Interview', 'Offer', 'Hired', 'Rejected']),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'is_terminal' => $this->faker->boolean(20),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }

    /** Explicit state helper for job */
    public function forJob($job): static
    {
        $jobId = is_string($job) ? $job : $job->id;

        return $this->state(fn () => ['job_opening_id' => $jobId]);
    }
}
