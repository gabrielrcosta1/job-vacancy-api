<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

final class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Candidate::factory()->count(5)->create();
    }
}
