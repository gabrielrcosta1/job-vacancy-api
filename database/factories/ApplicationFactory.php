<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::inRandomOrder()->first()->id,
            'vacancy_id' => Vacancy::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
