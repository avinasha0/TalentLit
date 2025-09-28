<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => tenant_id(), // will be null in CLI unless set
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'primary_email' => $this->faker->unique()->safeEmail(),
            'primary_phone' => $this->faker->optional(0.8)->phoneNumber(),
            'source' => $this->faker->randomElement(['Referral', 'Naukri', 'LinkedIn', 'Indeed', 'Company Website', 'Job Fair']),
            'resume_raw_text' => $this->faker->optional(0.6)->paragraphs(10, true),
            'resume_json' => $this->faker->optional(0.4)->randomElements([
                'skills' => ['PHP', 'Laravel', 'MySQL', 'JavaScript', 'React'],
                'experience' => $this->faker->numberBetween(1, 10),
                'education' => 'Bachelor of Technology',
            ]),
        ];
    }

    /** Explicit state helper */
    public function forTenant($tenant): static
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $this->state(fn () => ['tenant_id' => $tenantId]);
    }
}
