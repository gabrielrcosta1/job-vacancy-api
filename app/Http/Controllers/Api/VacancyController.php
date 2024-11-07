<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\VacancyDTO;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use App\Services\VacancyService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

final class VacancyController
{
    public function __construct(private VacancyService $vacancyService,  protected Vacancy $vacancy)
    {
        $this->vacancyService = $vacancyService;
    }

    public function index(VacancyRequest $request): AnonymousResourceCollection
    {
   
        $vacancies = $this->vacancyService->getAllVacancies($request);
        return VacancyResource::collection($vacancies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'status' => 'in:open,closed',
        ]);
    
        $data = $validator->validated();
        $vacancyDTO = VacancyDTO::fromRequest($data);
        $vacancy = $this->vacancyService->createVacancy($vacancyDTO);
    
        return new VacancyResource($vacancy);
    }
    
}
