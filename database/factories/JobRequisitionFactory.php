<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\JobRequisition;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobRequisition>
 */
class JobRequisitionFactory extends Factory
{
    protected $model = JobRequisition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'department_id' => Department::factory(),
            'location_id' => Location::factory(),
            'title' => $this->faker->jobTitle(),
            'headcount' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'rejected', 'closed']),
            'description' => $this->faker->paragraphs(3, true),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }
}
