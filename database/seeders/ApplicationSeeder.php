<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

final class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::factory()->count(5)->create();
    }
}
