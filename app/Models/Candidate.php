<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'candidates';
    protected $fillable = ['name', 'email', 'password', 'phone', 'birth_date', 'phone', 'password', 'verification_token', 'email_verified_at', 'address'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class);
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

    public function setPasswordAttribute($value)
    {
        if (strlen($value) === 60 && preg_match('/^\$2y\$/', $value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
