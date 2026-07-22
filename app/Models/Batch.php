<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches';
    protected $fillable = ['code', 'name', 'quota', 'start_date', 'end_date', 'status'];

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereDate('end_date', '>=', now())
              ->orWhereNull('end_date');
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('end_date', '<', now());
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function allocatedQuota(?int $excludeJobId = null): int
    {
        $query = $this->jobs();

        if ($excludeJobId !== null) {
            $query->where('id', '!=', $excludeJobId);
        }

        return (int) $query->sum('quota');
    }

    public function remainingQuota(?int $excludeJobId = null): int
    {
        return max(0, (int) $this->quota - $this->allocatedQuota($excludeJobId));
    }
}
