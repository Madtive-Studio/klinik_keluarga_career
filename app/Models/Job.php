<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'uuid', 'title', 'qualification', 'quota', 'user_id', 'description',
        'type', 'code', 'salary', 'is_show_salary', 'experience', 'batch_id', 'category_id',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function applies(): HasMany
    {
        return $this->hasMany(Apply::class, 'job_id', 'id')
            ->where('batch_id', $this->batch_id);
    }

    public function scopeForBatch(Builder $query, ?int $batchId): Builder
    {
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        $term = '%' . strtolower($keyword) . '%';

        return $query->whereRaw('LOWER(title) LIKE ?', [$term]);
    }

    public function scopeOfCategory(Builder $query, mixed $categoryId): Builder
    {
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        return $query;
    }

    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        if (!empty($type) && strtoupper($type) !== 'ALL') {
            $query->where('type', $type);
        }

        return $query;
    }

    public function scopeWithListingRelations(Builder $query): Builder
    {
        return $query->with(['category', 'batch']);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeFindByUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }
}
