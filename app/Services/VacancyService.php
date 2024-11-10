<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\VacancyDTO;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\VacancyRequest;
use App\Models\Vacancy;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;

final class VacancyService
{
    public function __construct(protected Vacancy $vacancy) {}

    public function getAvailableVacancies(CandidateRequest $request): LengthAwarePaginator
    {
        return $this->vacancy->where('status', 'open')
            ->filterBySalaryRange($request->input('salary_min'), $request->input('salary_max'))
            ->filterByKeyword($request->input('keyword'))->paginate(10);

    }

    public function getCompanyVacancies(VacancyRequest $vacancyRequest, int $companyId): LengthAwarePaginator
    {
        return $this->vacancy->where('company_id', $companyId)
            ->filterByStatus($vacancyRequest->status)
            ->filterByCreatedAt($vacancyRequest->created_at)
            ->paginate(10);
    }

    public function createVacancy(VacancyDTO $vacancyDTO): Vacancy
    {
        return $this->vacancy->create([
            'title' => $vacancyDTO->title,
            'description' => $vacancyDTO->description,
            'salary_min' => $vacancyDTO->salary_min,
            'salary_max' => $vacancyDTO->salary_max,
            'requirements' => $vacancyDTO->requirements,
            'benefits' => $vacancyDTO->benefits,
            'status' => $vacancyDTO->status,
            'company_id' => $vacancyDTO->company_id,
        ]);
    }

    public function deleteVacancy(string $vacancyId, int $companyId)
    {
        $vacancy = $this->vacancy->where('id', $vacancyId)
            ->where('company_id', $companyId)
            ->first();
        if (! $vacancy) {
            throw new HttpResponseException(response()->json([
                'error' => 'You do not have permission to delete this vacancy.',
            ], 403));
        }
        if ($vacancy->applications()->exists()) {
            throw new HttpResponseException(response()->json([
                'error' => 'It is not possible to delete the vacancy, as there are associated candidates.',
            ], 409));
        }
        $vacancy->delete();
    }

    public function updateVacancy(string $vacancyId, int $companyId, array $data): Vacancy
    {
        $vacancy = $this->vacancy->where('id', $vacancyId)
            ->where('company_id', $companyId)
            ->first();
        if (! $vacancy) {
            throw new HttpResponseException(response()->json([
                'error' => 'You do not have permission to delete this vacancy.',
            ], 403));
        }
        if ($vacancy->applications()->exists()) {
            throw new HttpResponseException(response()->json([
                'error' => 'It is not possible to delete the vacancy, as there are associated candidates.',
            ], 409));
        }
        $vacancy->update($data);

        return $vacancy;
    }
}
