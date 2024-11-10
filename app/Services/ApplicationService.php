<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\VacancyStatus;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApplicationService
{
    public function __construct(protected Application $application, protected Vacancy $vacancy) {}
    public function getApplicationsByUser(int $candidateId): LengthAwarePaginator
    {
        return  $this->application->where('candidate_id', $candidateId)
            ->with('vacancy')
            ->paginate(10);
    }

    public function apply(string $vacancyId, int $candidateId): Application|JsonResponse
    {
        $vacancy = $this->findOpenVacancy($vacancyId);

        $existingApplication = $this->application
            ->where('vacancy_id', $vacancyId)
            ->where('candidate_id', $candidateId)
            ->first();

        if ($existingApplication) {
            return $this->toggleApplicationStatus($existingApplication);
        }


        return $this->createApplication($vacancyId, $candidateId);
    }

    private function findOpenVacancy(string $id): Vacancy
    {
        $vacancy = $this->vacancy->where('id', $id)
            ->where('status', VacancyStatus::OPEN)
            ->first();

        if (! $vacancy) {
            throw new HttpResponseException(response()->json([
                'error' => 'Vacancy not found'
            ], 404));
        }

        return $vacancy;
    }

    private function toggleApplicationStatus(Application $application): Application
    {
        $application->status = $application->status === ApplicationStatus::PENDING
            ? ApplicationStatus::CANCELED
            : ApplicationStatus::PENDING;

        $application->save();

        return $application;
    }

    private function createApplication(string $vacancyId, int $candidateId): Application
    {
        return $this->application::create([
            'vacancy_id' => $vacancyId,
            'candidate_id' => $candidateId,
            'status' => ApplicationStatus::PENDING,
        ]);
    }
}
