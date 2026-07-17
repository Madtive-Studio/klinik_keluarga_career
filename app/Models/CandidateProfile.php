<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'education_level',
        'major',
        'university',
        'gpa',
        'years_of_experience',
        'last_position',
        'last_company',
        'city',
        'province',
        'expected_salary',
        'availability_date',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'availability_date' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
