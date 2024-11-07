<?php

declare(strict_types=1);

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->company = Company::factory()->create();
});

it('successfully creates a new vacancy', function () {
    $payload = [
        'title' => 'Desenvolvedor Backend',
        'description' => 'Vaga para desenvolvedor backend com experiência em PHP e Laravel.',
        'salary_min' => 3000,
        'salary_max' => 5000,
        'requirements' => ['PHP', 'Laravel', 'MySQL'],
        'benefits' => ['Vale Transporte', 'Vale Refeição'],
        'status' => 'open',
    ];

    $response = postJson('/api/jobs', $payload, ['X-Company-ID' => $this->company->id]);

    $response->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'salary_min',
                'salary_max',
                'requirements',
                'benefits',
                'status',
                'company_id',
                'created_at',
            ],
        ]);

    $this->assertDatabaseHas('vacancies', [
        'title' => $payload['title'],
        'company_id' => $this->company->id,
    ]);
});
