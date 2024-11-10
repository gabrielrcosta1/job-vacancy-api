<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
final class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'salary_min' => $this->faker->randomFloat(2, 3000, 5000),
            'salary_max' => $this->faker->randomFloat(2, 5000, 10000),
            'requirements' => $this->faker->sentences(3),
            'benefits' => $this->faker->sentences(2),
            'status' => $this->faker->randomElement(['open', 'closed']),
            'company_id' => Company::factory(),
        ];
    }
}
