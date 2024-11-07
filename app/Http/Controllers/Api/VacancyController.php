<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\VacancyDTO;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Services\VacancyService;

final class VacancyController
{
    public function __construct(private VacancyService $vacancyService)
    {
        $this->vacancyService = $vacancyService;
    }

    public function store(StoreVacancyRequest $request): VacancyResource
    {
        $data = $request->validated();

        $vacancyDTO = VacancyDTO::fromRequest($data);
        $vacancy = $this->vacancyService->createVacancy($vacancyDTO);

        return new VacancyResource($vacancy);
    }
}
