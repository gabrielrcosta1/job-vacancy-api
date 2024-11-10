<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Traits\FormatsDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class VacancyResource extends JsonResource
{
    use FormatsDate;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'requirements' => $this->requirements,
            'benefits' => $this->benefits,
            'status' => $this->status,
            'candidates_count' => $this->applications()->count(),
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
        ];
    }
}
