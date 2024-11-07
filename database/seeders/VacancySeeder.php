<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

final class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::all()->each(function ($company) {
            Vacancy::factory()->count(3)->create([
                'company_id' => $company->id,
            ]);
        });
    }
}
