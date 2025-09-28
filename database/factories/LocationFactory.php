<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Chennai', 'Bengaluru', 'Mumbai', 'Delhi', 'Hyderabad', 'Pune', 'Kolkata'];
        $city = $this->faker->randomElement($cities);

        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'name' => $city,
            'city' => $city,
            'country' => 'India',
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
