<?php
use App\Http\Requests\StoreUpdateVacancyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fails validation when creating a vacancy with invalid data', function () {
    $company = Company::factory()->create();

    $request = new StoreUpdateVacancyRequest();
    $data = [
        'title' => '',
        'description' => '',
        'salary_min' => -3000,
        'salary_max' => -5000,
        'requirements' => [],
        'benefits' => [],
        'company_id' => $company->id,
    ];

    $validator = Validator::make($data, $request->rules());
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
    expect($validator->errors()->has('salary_min'))->toBeTrue();
    expect($validator->errors()->has('salary_max'))->toBeTrue();
});

