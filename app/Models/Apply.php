<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
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

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    /**
     * Check if application is in review
     */
    public function isInReview(): bool
    {
        return $this->status === ApplicationStatus::IN_REVIEW;
    }

    /**
     * Check if application is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === ApplicationStatus::NOT_SUITABLE;
    }

    /**
     * Check if application is shortlisted
     */
    public function isShortlisted(): bool
    {
        return $this->status === ApplicationStatus::SHORTLISTED;
    }

    /**
     * Check if application is accepted (hired)
     */
    public function isHired(): bool
    {
        return $this->status === ApplicationStatus::HIRED;
    }
}