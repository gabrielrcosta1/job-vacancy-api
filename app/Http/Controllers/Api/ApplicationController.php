<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Enums\VacancyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Vacancy;
use App\Services\ApplicationService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

final class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $applicationService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CandidateRequest $request)
    {
        $candidateId = (int) $request->header('X-User-ID');

        $application = $this->applicationService->apply($request->id,$candidateId);

        return new ApplicationResource($application);

    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
