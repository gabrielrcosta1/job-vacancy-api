<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\VacancyDTO;
use App\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;

;

final class VacancyService
{
    public function getAllVacancies($vacancyRequest): LengthAwarePaginator
    {
        return Vacancy::filterByStatus($vacancyRequest->status)
        ->filterByCreatedAt($vacancyRequest->created_at)
        ->paginate(10);
    }
    public function createVacancy(VacancyDTO $vacancyDTO): Vacancy
    {
        return Vacancy::create([
            'title' => $vacancyDTO->title,
            'description' => $vacancyDTO->description,
            'salary_min' => $vacancyDTO->salary_min,
            'salary_max' => $vacancyDTO->salary_max,
            'requirements' => $vacancyDTO->requirements,
            'benefits' => $vacancyDTO->benefits,
            'status' => $vacancyDTO->status,
            'company_id' => $vacancyDTO->company_id,
        ]);
    }
}
