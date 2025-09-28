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
            'tenant_id' => Tenant::factory(),
            'department_id' => Department::factory(),
            'location_id' => Location::factory(),
            'title' => $this->faker->jobTitle(),
            'headcount' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'rejected', 'closed']),
            'description' => $this->faker->paragraphs(3, true),
        ];
    }
}
