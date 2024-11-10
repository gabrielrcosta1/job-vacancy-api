<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\VacancyDTO;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\VacancyRequest;
use App\Models\Vacancy;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

final class VacancyService
{
    public function __construct(protected Vacancy $vacancy) {}

    public function getAvailableVacancies(CandidateRequest $request): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey('available_vacancies', $request->all());

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            return $this->vacancy->where('status', 'open')
                ->filterBySalaryRange($request->input('salary_min'), $request->input('salary_max'))
                ->filterByKeyword($request->input('keyword'))
                ->paginate(10);
        });
    }

    public function getCompanyVacancies(VacancyRequest $vacancyRequest, int $companyId): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey("company_vacancies_{$companyId}", $vacancyRequest->all());

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($vacancyRequest, $companyId) {
            return $this->vacancy->where('company_id', $companyId)
                ->filterByStatus($vacancyRequest->status)
                ->filterByCreatedAt($vacancyRequest->created_at)
                ->paginate(10);
        });
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

    public function deleteVacancy(string $vacancyId, int $companyId): void
    {
        $vacancy = $this->findVacancyByCompany($vacancyId, $companyId);
        $this->ensureVacancyCanBeModified($vacancy);

        $vacancy->delete();
    }

    public function updateVacancy(string $vacancyId, int $companyId, VacancyDTO $vacancyDTO): Vacancy
    {
        $vacancy = $this->findVacancyByCompany($vacancyId, $companyId);
        $this->ensureVacancyCanBeModified($vacancy);
        $vacancy->update($vacancyDTO->toArray());

        return $vacancy;
    }

    private function generateCacheKey(string $base, array $params): string
    {
        return $base.':'.md5(json_encode($params));
    }

    private function findVacancyByCompany(string $vacancyId, int $companyId): Vacancy
    {
        $vacancy = $this->vacancy->where('id', $vacancyId)
            ->where('company_id', $companyId)
            ->first();

        if (! $vacancy) {
            throw new HttpResponseException(response()->json([
                'error' => 'You do not have permission to modify this vacancy.',
            ], 403));
        }

        return $vacancy;
    }

    private function ensureVacancyCanBeModified(Vacancy $vacancy): void
    {
        if ($vacancy->applications()->exists()) {
            throw new HttpResponseException(response()->json([
                'error' => 'It is not possible to modify this vacancy, as there are associated candidates.',
            ], 409));
        }
    }
}
