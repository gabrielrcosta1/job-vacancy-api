<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::controller(VacancyController::class)->group(function () {
    Route::get('/jobs', 'index');
    Route::post('/jobs', 'store');
});

Route::post('/teste', [TestController::class, 'store']);