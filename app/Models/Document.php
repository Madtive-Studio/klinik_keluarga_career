<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\DocumentCategory;
use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'file',
        'type',
        'candidate_id',
        'category',
        'status',
        'is_required',
        'verification_notes',
        'verified_at',
    ];
    protected $casts = [
        'type' => DocumentType::class,
        'category' => DocumentCategory::class,
        'status' => DocumentStatus::class,
        'is_required' => 'boolean',
        'verified_at' => 'datetime',
    ];
    protected $appends = ['file_url', 'type_badge', 'type_label'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function getFileUrlAttribute()
    {
        return $this->file ? Storage::url($this->file) : null;
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'CV' => 'badge-primary',
            'CERTIFICATE' => 'badge-success',
            'PORTFOLIO' => 'badge-info',
            'OTHERS' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'CV' => 'CV',
            'CERTIFICATE' => 'Sertifikat',
            'PORTFOLIO' => 'Portfolio',
            'OTHERS' => 'Lainnya',
            default => (string)$this->type,
        };
    }

    public function isVerified(): bool
    {
        return $this->status === DocumentStatus::VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->status === DocumentStatus::REJECTED;
    }

    public function markAsVerified(string $notes = null): void
    {
        $this->update([
            'status' => DocumentStatus::VERIFIED,
            'verification_notes' => $notes,
            'verified_at' => now(),
        ]);
    }

    public function markAsRejected(string $notes): void
    {
        $this->update([
            'status' => DocumentStatus::REJECTED,
            'verification_notes' => $notes,
        ]);
    }
}
