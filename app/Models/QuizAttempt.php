<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'session_token',
        'email',
        'name',
        'status',
        'result_type_id',
        'score_breakdown',
        'completed_at',
        'paid_at',
        'selected_question_ids',
        'dimension_scores',
    ];

    protected $casts = [
        'score_breakdown'       => 'array',
        'completed_at'          => 'datetime',
        'paid_at'               => 'datetime',
        'selected_question_ids' => 'array',
        'dimension_scores'      => 'array',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function resultType(): BelongsTo
    {
        return $this->belongsTo(ResultType::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }

    /**
     * Returns the randomly-selected questions for this attempt, in the correct order.
     * Falls back to all quiz questions for backward-compatible attempts (no selected_question_ids).
     */
    public function getSelectedQuestionsAttribute(): Collection
    {
        $ids = $this->selected_question_ids;

        if (empty($ids)) {
            return $this->quiz->questions;
        }

        // Preserve the shuffled order by sorting by the position in $ids array
        $questions = QuizQuestion::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $questions->get($id))
            ->filter()
            ->values();
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'paid']);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function hasPaidOrder(): bool
    {
        return $this->order()->where('status', 'paid')->exists();
    }
}
