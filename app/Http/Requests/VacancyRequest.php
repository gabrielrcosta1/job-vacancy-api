<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

final class VacancyRequest extends FormRequest
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
            'status' => 'nullable|string|in:open,closed',
            'created_at' => 'nullable|date',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->header('X-Company-ID')) {
            throw new HttpResponseException(response()->json([
                'error' => 'X-Company-ID header is required',
            ], 422));
        }
    }
}
