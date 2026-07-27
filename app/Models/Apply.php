<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apply extends Model
{
    use HasFactory;

    protected $table = 'applies';
    protected $fillable = [
        'uuid',
        'candidate_id',
        'document_id',
        'job_id',
        'batch_id',
        'cover_letter',
        'status',
        'description',
        'auto_score',
        'score_recommendation',
        'score_breakdown',
        'scored_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'score_breakdown' => 'array',
        'scored_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }

    public function cv(): BelongsTo
    {
        return $this->document();
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function applyDocuments(): HasMany
    {
        return $this->hasMany(ApplyDocument::class);
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Document::class, ApplyDocument::class, 'apply_id', 'id', 'id', 'document_id');
    }
}