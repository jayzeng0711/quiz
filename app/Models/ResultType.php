<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResultType extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'code',
        'title',
        'description',
        'report_content',
        'meta',
        'sort_order',
    ];

    protected $casts = [
        'meta' => 'array',
        'sort_order' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
