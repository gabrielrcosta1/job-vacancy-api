<?php

use App\Models\Vacancy;
use App\Models\Company;
use App\Models\Candidate;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Enums\ApplicationStatus;
use App\Enums\VacancyStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->company = Company::factory()->create();
    $this->candidate = Candidate::factory()->create(); 
});


it('applies and unapplies for a vacancy', function () {
    $vacancy = Vacancy::factory()->create([
        'status' => VacancyStatus::OPEN,
        'company_id' => $this->company->id
    ]);

    $applicationService = app()->make(ApplicationService::class);

    $application = $applicationService->apply($vacancy->id, $this->candidate->id);
    expect($application->status)->toBe(ApplicationStatus::PENDING);

    $application = $applicationService->apply($vacancy->id, $this->candidate->id);
    expect($application->status)->toBe(ApplicationStatus::CANCELED);
});


it('returns the correct response format for applications', function () {
    $vacancy = Vacancy::factory()->create([
        'status' => VacancyStatus::OPEN,
        'company_id' => $this->company->id
    ]);

    $application = Application::factory()->create([
        'vacancy_id' => $vacancy->id,
        'candidate_id' => $this->candidate->id,
        'status' => ApplicationStatus::PENDING,
    ]);

    $applicationResource = new \App\Http\Resources\ApplicationResource($application);
    $applicationArray = $applicationResource->toArray(new Request);

    expect($applicationArray)->toHaveKeys([
        'id', 'vacancy_id', 'status', 'created_at', 'updated_at'
    ])
    ->and($applicationArray['status'])->toBe(ApplicationStatus::PENDING->value);
});
