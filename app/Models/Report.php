<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'order_id',
        'result_type_id',
        'access_token',
        'status',
        'pdf_path',
        'rendered_content',
        'generated_at',
        'delivered_at',
    ];

    protected $casts = [
        'rendered_content' => 'array',
        'generated_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function resultType(): BelongsTo
    {
        return $this->belongsTo(ResultType::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    public function isGenerated(): bool
    {
        return $this->status === 'generated' || $this->status === 'delivered';
    }
}
