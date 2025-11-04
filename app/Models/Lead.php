<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'owner_id',
        'stage',
        'stage_date',
        'last_comment',
        'created_by',
    ];

    protected $casts = [
        'stage_date' => 'datetime',
    ];

    public const STAGES = [
        'new',
        'no_answer',
        'first_call',
        'presentation',
        'follow_up',
        'negotiation',
        'hot',
        'cold',
        'won',
        'lost',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LeadComment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(LeadHistory::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
