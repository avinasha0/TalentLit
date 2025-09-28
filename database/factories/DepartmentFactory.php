<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'name' => $this->faker->randomElement(['Engineering', 'Sales', 'HR', 'Marketing', 'Finance', 'Operations']),
            'code' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }
}
