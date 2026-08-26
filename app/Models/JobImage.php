<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JobImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'job_uuid',
        'original_name',
        'hash_name',
        'size',
        'extension',
        'mime_type',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'size' => 'double',
    ];

    protected $appends = ['url'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->hash_name);
    }
}
