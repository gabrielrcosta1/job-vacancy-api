<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\VacancyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\StoreUpdateVacancyRequest;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
use App\Services\VacancyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class VacancyController extends Controller
{
    public function __construct(private VacancyService $vacancyService)
    {
        $this->vacancyService = $vacancyService;
    }

    public function available(CandidateRequest $request): VacancyCollection|JsonResponse
    {

        $vacancies = $this->vacancyService->getAvailableVacancies($request);

        return new VacancyCollection($vacancies);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(VacancyRequest $request): VacancyCollection|JsonResponse
    {

        $vacancies = $this->vacancyService->getCompanyVacancies($request, (int) $request->header('X-Company-ID'));

        return new VacancyCollection($vacancies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateVacancyRequest $request): VacancyResource
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
    public function update(StoreUpdateVacancyRequest $request, string $id)
    {
        $companyId = (int) $request->header('X-Company-ID');
        $vacancy = $this->vacancyService->updateVacancy($id, $companyId, $request->validated());

        return new VacancyResource($vacancy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VacancyRequest $request, string $id): Response
    {
        $this->vacancyService->deleteVacancy($id, (int) $request->header('X-Company-ID'));

        return response()->noContent();

    }
}
