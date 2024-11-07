<?php

declare(strict_types=1);

use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::post('/jobs', [VacancyController::class, 'store']);
Route::get('/jobs', function () {
    return response('Hello World', 200)
        ->header('Content-Type', 'text/plain');
});
