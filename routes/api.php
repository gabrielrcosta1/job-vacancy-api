<?php

declare(strict_types=1);

use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/jobs/available', [VacancyController::class, 'available']);
Route::post('/jobs/{id}/apply', [VacancyController::class, 'apply']);
Route::apiResource('jobs', VacancyController::class);
