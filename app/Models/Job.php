<?php

namespace App\Models;

use App\Enums\EducationLevel;
use App\Services\JobImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    protected $fillable = [
        'uuid', 'title', 'qualification', 'quota', 'user_id', 'description', 'type', 'code', 'images',
        'salary_min', 'salary_max', 'is_show_salary', 'experience', 'batch_id', 'category_id',
    ];

    protected $casts = [
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'is_show_salary' => 'boolean',
        'images' => 'array',
    ];

    public function getSalaryDisplayAttribute(): string
    {
        return formatSalaryRange($this->salary_min, $this->salary_max);
    }

    public function getImageUrlsAttribute(): array
    {
        return app(JobImageService::class)->resolveUrls($this->images);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_urls[0] ?? asset('assets/candidate/images/job-placeholder.png');
    }

    protected static function booted(): void
    {
        static::deleting(function (Job $job) {
            app(JobImageService::class)->deletePaths($job->images ?? []);
        });
    }

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

    public function criteria(): HasOne
    {
        return $this->hasOne(JobCriteria::class);
    }

    public function candidateMeetsEducation(?string $candidateEducationLevel): bool
    {
        $this->loadMissing('criteria');
        $minEducation = $this->criteria?->min_education;

        if (!$minEducation) {
            return true;
        }

        if (!$candidateEducationLevel) {
            return false;
        }

        return EducationLevel::rankOf($candidateEducationLevel) >= EducationLevel::rankOf($minEducation);
    }
}
