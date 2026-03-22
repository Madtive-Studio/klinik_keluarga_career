<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable = ['uuid', 'title', 'qualification', 'quota', 'user_id', 'description', 'type', 'code', 'salary', 'is_show_salary', 'experience', 'batch_id', 'category_id', 'user_id'];

    /**
     * Get the user that owns the Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the user that owns the Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all of the applies for the Job
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applies(): HasMany
    {
        return $this->hasMany(Apply::class, 'job_id', 'id')->where('batch_id', $this->batch_id);
    }
}
