<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vacancy_id' => $this->vacancy_id,
            'status' => $this->status->value,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
        ];
    }
}
