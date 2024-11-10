<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
