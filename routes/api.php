<?php

declare(strict_types=1);

use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::apiResource('jobs', VacancyController::class);
