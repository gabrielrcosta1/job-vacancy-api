<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\VacancyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use App\Services\VacancyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class VacancyController extends Controller
{
    public function __construct(private VacancyService $vacancyService, protected Vacancy $repository)
    {
        $this->vacancyService = $vacancyService;
    }

    public function available(CandidateRequest $request): VacancyCollection|JsonResponse
    {
        if (! $request->header('X-User-ID')) {
            return response()->json(['error' => 'X-User-ID header is required'], 400);
        }

        $vacancies = $this->vacancyService->getAvailableVacancies($request);

        return new VacancyCollection($vacancies);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(VacancyRequest $request): VacancyCollection|JsonResponse
    {
        $companyId = $request->header('X-Company-ID');
        if (! $companyId) {
            return response()->json(['error' => 'X-Company-ID header is required'], 422);
        }
        $vacancies = $this->vacancyService->getCompanyVacancies($request, (int) $companyId);

        return new VacancyCollection($vacancies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacancyRequest $request): VacancyResource
    {
        $vacancyDTO = VacancyDTO::fromRequest($request->validated());

        $vacancy = $this->vacancyService->createVacancy($vacancyDTO);

        return new VacancyResource($vacancy);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vacancy = $this->repository->findOrFail($id);
        $vacancy->delete();

        return response()->noContent();
    }
}
