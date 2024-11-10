<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Vacancy extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'salary_min',
        'salary_max',
        'requirements',
        'benefits',
        'status',
        'company_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeFilterByStatus($query, $status): mixed
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeFilterByCreatedAt($query, $date): mixed
    {
        if ($date) {
            return $query->whereDate('created_at', $date);
        }

        return $query;
    }

    public function scopeFilterBySalaryRange($query, $salaryMin, $salaryMax): mixed
    {
        if (!is_null($salaryMin)) {
            $query->where('salary_min', '>=', $salaryMin);
        }
        if (!is_null($salaryMax)) {
            $query->where('salary_max', '<=', $salaryMax);
        }
        return $query;
    }

    public function scopeFilterByKeyword($query, $keyword): mixed
    {
        if (!is_null($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function casts(): array
    {
        return [
            'requirements' => 'array',
            'benefits' => 'array',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
        ];
    }
}
