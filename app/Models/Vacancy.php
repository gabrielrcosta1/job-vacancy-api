<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Vacancy extends Model
{
    use HasFactory;

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
