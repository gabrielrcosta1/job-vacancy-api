<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Application extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'candidate_id', 'vacancy_id', 'status',
    ];
    protected $attributes = [
        'status' => ApplicationStatus::PENDING,
    ];

    public function casts(): array{
        return [
            'status' => ApplicationStatus::class
        ];
    }
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }
}
