<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\VacancyStatus;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApplicationService
{
    public function __construct(protected Application $application, protected Vacancy $vacancy) {}

    /**
     * I left this function very simple,
     * but there is the possibility of evolving it later
     * and letting the candidate apply for the vacancy again if they wanted
     */
    public function apply(string $id, int $candidateId): Application|JsonResponse
    {
        $vacancy = $this->vacancy->where('id', $id)
            ->where('status', VacancyStatus::OPEN)
            ->first();
        if (! $vacancy) {
            throw new HttpResponseException(response()->json([
                'error' => 'Vacancy not found',
            ], 404));
        }

        $application = $this->application->where('vacancy_id', $id)
            ->where('candidate_id', $candidateId)
            ->first();

        if ($application) {
            $application->status = ApplicationStatus::CANCELED;
            $application->save();

           return $application;
        }
        $application = $this->application::create([
            'vacancy_id' => $id,
            'candidate_id' => $candidateId,
        ]);
        return $application;
    }
}
