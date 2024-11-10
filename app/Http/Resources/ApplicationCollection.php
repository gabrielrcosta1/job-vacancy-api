<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($application) {
                return [
                    'id' => $application->id,
                    'vacancy' => [
                        'id' => $application->vacancy->id,
                        'title' => $application->vacancy->title,
                        'description' => $application->vacancy->description,
                        'status' => $application->vacancy->status,
                    ],
                    'applied_at' => Carbon::parse($application->created_at)->format("d/m/Y H:i")
                ];
            }),
        ];
    }
}
