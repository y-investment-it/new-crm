<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $stage = $this->faker->randomElement(Lead::STAGES);

        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'source' => $this->faker->randomElement(['web', 'referral', 'phone', 'event']),
            'owner_id' => null,
            'stage' => $stage,
            'stage_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'last_comment' => $this->faker->sentence(),
            'created_by' => null,
        ];
    }
}
