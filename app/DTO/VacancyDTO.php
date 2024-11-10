<?php

declare(strict_types=1);

namespace App\DTO;

final class VacancyDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public ?float $salary_min,
        public ?float $salary_max,
        public array $requirements,
        public array $benefits,
        public string $status,
        public int $company_id
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['title'],
            $data['description'],
            $data['salary_min'] ?? null,
            $data['salary_max'] ?? null,
            $data['requirements'] ?? [],
            $data['benefits'] ?? [],
            $data['status'] ?? 'open',
            $data['company_id']
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'requirements' => $this->requirements,
            'benefits' => $this->benefits,
            'status' => $this->status,
            'company_id' => $this->company_id,
        ];
    }
}
