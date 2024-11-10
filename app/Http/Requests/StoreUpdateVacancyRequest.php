<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class StoreUpdateVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'requirements' => 'required|array',
            'benefits' => 'required|array',
            'company_id' => 'required|exists:companies,id',
        ];

        return $rules;
    }

    public function failedValidation(Validator $validator): void
    {

        throw new HttpResponseException(response()->json([
            'message' => 'Validation errors',

            'data' => $validator->errors(),

        ], 422));

    }

    protected function prepareForValidation(): void
    {
        if (! $this->header('X-Company-ID')) {
            throw new HttpResponseException(response()->json([
                'error' => 'X-Company-ID header is required',
            ], 422));
        }
        $this->merge([
            'company_id' => (int) $this->header('X-Company-ID'),
        ]);
    }
}
