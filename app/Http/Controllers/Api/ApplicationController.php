<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationCollection;
use App\Http\Resources\ApplicationResource;
use App\Services\ApplicationService;

final class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $applicationService) {}

    public function index(ApplicationRequest $request): ApplicationCollection
    {

        $applications = $this->applicationService->getApplicationsByUser((int) $request->header('X-User-ID'));

        return new ApplicationCollection($applications);
    }

    public function store(ApplicationRequest $request): ApplicationResource
    {

        $application = $this->applicationService->apply($request->id, (int) $request->header('X-User-ID'));

        return new ApplicationResource($application);

    }
}
