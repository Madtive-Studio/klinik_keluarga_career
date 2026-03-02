<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleInterview extends Model
{
    use HasFactory;
    protected $table = 'schedule_interviews';
    protected $fillable = ['uuid', 'code', 'title', 'link', 'start_datetime', 'is_online', 'status', 'end_datetime', 'description', 'job_id', 'candidate_id', 'batch_id', 'apply_id'];

    /**
     * Get the job that owns the ScheduleInterview
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the candidate that owns the ScheduleInterview
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the batch that owns the ScheduleInterview
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the apply that owns the ScheduleInterview
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apply(): BelongsTo
    {
        return $this->belongsTo(Apply::class);
    }
}
