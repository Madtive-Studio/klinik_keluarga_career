<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'candidates';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'verification_token',
        'email_verified_at',
        'address',
        'ktp_number',
        'passport_number',
        'driving_license_number',
        'gender',
        'education_background',
        'work_experience',
        'identity_verified',
        'document_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'identity_verified' => 'boolean',
        'document_completed' => 'boolean',
        'password' => 'hashed',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function identities(): HasMany
    {
        return $this->hasMany(CandidateIdentity::class);
    }

    /**
     * Get all of the comments for the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CVs()
    {
        return $this->hasMany(CV::class);
    }

    /**
     * Get all of the applies for the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applies()
    {
        return $this->hasMany(Apply::class, 'candidate_id', 'id');
    }

    /**
     * Get all of the interviews for the Candidate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function interviews()
    {
        return $this->hasMany(ScheduleInterview::class);
    }

    /**
     * Check if candidate has completed all required documents
     */
    public function hasCompletedDocuments(): bool
    {
        $requiredDocuments = $this->documents()->where('is_required', true)->count();
        $verifiedDocuments = $this->documents()
            ->where('is_required', true)
            ->where('status', 'VERIFIED')
            ->count();

        return $requiredDocuments > 0 && $requiredDocuments === $verifiedDocuments;
    }

    /**
     * Check if candidate has completed all identity verification
     */
    public function hasCompletedIdentity(): bool
    {
        // Check basic identity fields
        $basicFields = [
            $this->ktp_number,
            $this->gender,
            $this->education_background,
        ];

        return !collect($basicFields)->contains(null);
    }

    /**
     * Get document completeness percentage
     */
    public function getDocumentCompletenessPercentage(): float
    {
        $totalRequired = $this->documents()->where('is_required', true)->count();
        if ($totalRequired === 0) {
            return 100;
        }

        $verifiedCount = $this->documents()
            ->where('is_required', true)
            ->where('status', 'VERIFIED')
            ->count();

        return ($verifiedCount / $totalRequired) * 100;
    }

    /**
     * Get identity completeness percentage
     */
    public function getIdentityCompletenessPercentage(): float
    {
        $required = ['ktp_number', 'gender', 'education_background'];
        $completed = 0;

        foreach ($required as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return ($completed / count($required)) * 100;
    }
}
