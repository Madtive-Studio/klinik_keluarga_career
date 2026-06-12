<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\IdentityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CandidateIdentity extends Model
{
    use HasFactory;

    protected $table = 'candidate_identities';
    protected $fillable = [
        'candidate_id',
        'identity_type',
        'identity_number',
        'document_file',
        'status',
        'verification_notes',
        'verified_at',
    ];

    protected $casts = [
        'identity_type' => IdentityType::class,
        'status' => DocumentStatus::class,
        'verified_at' => 'datetime',
    ];

    protected $appends = ['document_url'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_file ? Storage::url($this->document_file) : null;
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
