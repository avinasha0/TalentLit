<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\JobOpening;
use App\Models\JobRequisition;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOpening>
 */
class JobOpeningFactory extends Factory
{
    protected $model = JobOpening::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->jobTitle();

        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'requisition_id' => JobRequisition::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'department_id' => Department::factory(),
            'location_id' => Location::factory(),
            'employment_type' => $this->faker->randomElement(['full_time', 'part_time', 'contract', 'intern']),
            'status' => $this->faker->randomElement(['draft', 'published', 'closed']),
            'openings_count' => $this->faker->numberBetween(1, 5),
            'description' => $this->faker->paragraphs(5, true),
            'published_at' => $this->faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }

    /** Published job state */
    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /** Draft job state */
    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /** Closed job state */
    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => 'closed',
            'published_at' => $this->faker->dateTimeBetween('-60 days', '-1 day'),
        ]);
    }
}
