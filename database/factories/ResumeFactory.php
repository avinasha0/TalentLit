<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resume>
 */
class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'candidate_id' => Candidate::factory(),
            'disk' => 'public',
            'path' => 'resumes/' . $this->faker->uuid() . '.pdf',
            'filename' => $this->faker->word() . '_resume.pdf',
            'mime' => 'application/pdf',
            'size' => $this->faker->numberBetween(100000, 5000000),
            'parsed_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }
}
