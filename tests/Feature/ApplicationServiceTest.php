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

// Configuração antes de cada teste
beforeEach(function () {
    // Cria uma empresa e um candidato para garantir que o vacancy_id e o candidate_id sejam válidos
    $this->company = Company::factory()->create();
    $this->candidate = Candidate::factory()->create(); // Cria um candidato para usar nos testes
});

// Teste para aplicar e desaplicar uma candidatura
it('applies and unapplies for a vacancy', function () {
    $vacancy = Vacancy::factory()->create([
        'status' => VacancyStatus::OPEN,
        'company_id' => $this->company->id
    ]);

    $applicationService = app()->make(ApplicationService::class);

    // Aplica para a vaga
    $application = $applicationService->apply($vacancy->id, $this->candidate->id);
    expect($application->status)->toBe(ApplicationStatus::PENDING);

    // Cancela a aplicação
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

    // Instância do resource com o Application e uma instância de Request
    $applicationResource = new \App\Http\Resources\ApplicationResource($application);
    $applicationArray = $applicationResource->toArray(new Request);

    // Verifica os campos necessários no array retornado
    expect($applicationArray)->toHaveKeys([
        'id', 'vacancy_id', 'status', 'created_at', 'updated_at'
    ])
    ->and($applicationArray['status'])->toBe(ApplicationStatus::PENDING->value);
});
