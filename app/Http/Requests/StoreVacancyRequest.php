<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreVacancyRequest extends FormRequest
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
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'salary_min' => 'required|numeric',
            'salary_max' => 'required|numeric',
            'requirements' => 'required|array',
            'benefits' => 'required|array',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'description.required' => 'A descrição é obrigatória.',
            'salary_min.required' => 'A faixa salarial mínima é obrigatória.',
            'salary_max.required' => 'A faixa salarial máxima é obrigatória.',
            'requirements.required' => 'Os requisitos são obrigatórios.',
            'benefits.required' => 'Os benefícios são obrigatórios.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'company_id' => (int) $this->header('X-Company-ID'),
        ]);
    }
}
