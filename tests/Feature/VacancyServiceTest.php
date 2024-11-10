<?php

use App\Models\Company;
use App\Models\Vacancy;
use App\Models\Application;
use App\DTO\VacancyDTO;
use App\Services\VacancyService;
use App\Enums\VacancyStatus;
use App\Http\Requests\CandidateRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\HttpResponseException;

uses(RefreshDatabase::class);

// Configuração antes de cada teste
beforeEach(function () {
    // Cria uma empresa para evitar erros de chave estrangeira
    $this->company = Company::factory()->create();
});

// Teste de criação de vaga com dados válidos
it('creates a vacancy with valid data', function () {
    $vacancyDTO = new VacancyDTO(
        title: 'Developer',
        description: 'A developer position',
        salary_min: 3000,
        salary_max: 5000,
        requirements: ['PHP', 'Laravel'],
        benefits: ['Health insurance', 'Remote work'],
        status: VacancyStatus::OPEN->value,
        company_id: $this->company->id
    );

    $vacancyService = app()->make(VacancyService::class);
    $vacancy = $vacancyService->createVacancy($vacancyDTO);

    expect($vacancy)->toBeInstanceOf(Vacancy::class)
        ->and($vacancy->title)->toBe('Developer')
        ->and($vacancy->requirements)->toBe(['PHP', 'Laravel'])
        ->and($vacancy->benefits)->toBe(['Health insurance', 'Remote work']);
});



it('fails to update a vacancy with candidates', function () {
    $vacancy = Vacancy::factory()->create([
        'company_id' => $this->company->id,
        'status' => VacancyStatus::OPEN
    ]);
    Application::factory()->create(['vacancy_id' => $vacancy->id]);

    $vacancyDTO = new VacancyDTO(
        title: 'Updated Position',
        description: 'Updated description',
        salary_min: 4000,
        salary_max: 7000,
        requirements: ['Updated requirements'],
        benefits: ['Updated benefits'],
        status: VacancyStatus::CLOSED->value,
        company_id: $this->company->id
    );

    $vacancyService = app()->make(VacancyService::class);

    try {
        // Tente atualizar a vaga e espere uma exceção
        $vacancyService->updateVacancy($vacancy->id, $this->company->id, $vacancyDTO);
    } catch (HttpResponseException $e) {
        // Verifique o conteúdo JSON da resposta
        $response = json_decode($e->getResponse()->getContent(), true);
        expect($response['error'])->toBe('It is not possible to modify this vacancy, as there are associated candidates.');
        return;
    }

    // Falha o teste se a exceção não for lançada
    $this->fail('HttpResponseException was not thrown as expected.');
});


// Teste de listagem de vagas com filtros
it('lists vacancies with filters', function () {
    Vacancy::factory()->count(5)->create(['status' => VacancyStatus::OPEN, 'company_id' => $this->company->id]);
    Vacancy::factory()->count(3)->create(['status' => VacancyStatus::CLOSED, 'company_id' => $this->company->id]);

    $vacancyService = app()->make(VacancyService::class);
    $request = new CandidateRequest(['status' => VacancyStatus::OPEN->value]);

    $vacancies = $vacancyService->getAvailableVacancies($request);

    expect($vacancies->total())->toBe(5);
});
