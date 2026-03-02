<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apply extends Model
{
    use HasFactory;

    protected $table = 'applies';
    protected $fillable = ['uuid', 'candidate_id', 'cv_id', 'job_id', 'batch_id', 'cover_letter', 'status', 'description', 'created_at', 'updated_at'];

    /**
     * Get the job that owns the Apply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    /**
     * Get the batch that owns the Apply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    /**
     * Get the cv that owns the Apply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(CV::class, 'cv_id', 'id');
    }

    /**
     * Get the candidate that owns the Apply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
