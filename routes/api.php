<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/jobs/available', [VacancyController::class, 'available']);
Route::post('/jobs/{id}/apply', [ApplicationController::class, 'store']);
Route::get('/jobs/applications', [ApplicationController::class, 'index']);
Route::apiResource('jobs', VacancyController::class);
