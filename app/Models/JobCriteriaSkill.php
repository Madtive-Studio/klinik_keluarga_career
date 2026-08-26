<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCriteriaSkill extends Model
{
    use HasFactory;

    protected $table = 'job_criteria_skills';

    protected $fillable = [
        'job_criteria_id',
        'skill_name',
    ];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(JobCriteria::class, 'job_criteria_id');
    }
}
