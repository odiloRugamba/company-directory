<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_id',
        'incubator_id',
        'status',
        'motivation',
        'pitch_deck_url',
        'reviewer_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
        'reviewed_at' => 'datetime',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(User::class, 'startup_id');
    }

    public function incubator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'incubator_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeByStatus(mixed $query, ApplicationStatus|string $status): mixed
    {
        return $query->where('status', $status instanceof ApplicationStatus ? $status->value : $status);
    }
}
