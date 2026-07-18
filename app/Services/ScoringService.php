<?php

namespace App\Services;

use App\Enums\EducationLevel;
use App\Enums\ScoreRecommendation;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobCriteria;

class ScoringService
{
    public function calculate(Candidate $candidate, Job $job, string $coverLetter): array
    {
        $candidate->loadMissing('profile');
        $job->loadMissing('criteria');

        $criteria = $job->criteria ?? new JobCriteria(JobCriteria::defaultsForJob($job->id));
        $profile = $candidate->profile;

        $educationScore = $this->scoreEducation($profile?->education_level, $criteria);
        $experienceScore = $this->scoreExperience($profile?->years_of_experience ?? 0, $criteria, $job->experience);
        $profileScore = $this->scoreProfileCompleteness($profile, $criteria);
        $coverLetterScore = $this->scoreCoverLetter($coverLetter, $criteria);

        $breakdown = [
            'education' => $educationScore,
            'experience' => $experienceScore,
            'profile' => $profileScore,
            'cover_letter' => $coverLetterScore,
        ];

        $totalScore = array_sum($breakdown);
        $recommendation = $this->resolveRecommendation($totalScore, $criteria);

        return [
            'score' => $totalScore,
            'recommendation' => $recommendation->value,
            'breakdown' => $breakdown,
        ];
    }

    private function scoreEducation(?string $candidateLevel, JobCriteria $criteria): int
    {
        $weight = $criteria->weight_education;

        if (!$criteria->min_education) {
            return $candidateLevel ? $weight : 0;
        }

        if (EducationLevel::rankOf($candidateLevel) >= EducationLevel::rankOf($criteria->min_education)) {
            return $weight;
        }

        return 0;
    }

    private function scoreExperience(int $years, JobCriteria $criteria, ?string $jobExperience): int
    {
        $weight = $criteria->weight_experience;
        $minimum = parseJobExperienceYears($jobExperience);

        if ($minimum === 0) {
            return $weight;
        }

        $ratio = min($years / $minimum, 1);

        return (int) round($ratio * $weight);
    }

    private function scoreProfileCompleteness($profile, JobCriteria $criteria): int
    {
        $weight = $criteria->weight_profile;

        if (!$profile) {
            return 0;
        }

        $fields = ['education_level', 'major', 'years_of_experience', 'city', 'province'];
        $filled = 0;

        foreach ($fields as $field) {
            $value = $profile->{$field};
            if ($value !== null && $value !== '') {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * $weight);
    }

    private function scoreCoverLetter(string $coverLetter, JobCriteria $criteria): int
    {
        $weight = $criteria->weight_cover_letter;
        $length = strlen(trim(strip_tags($coverLetter)));

        if ($length >= 100) {
            return $weight;
        }

        if ($length >= 50) {
            return (int) round($weight * 0.7);
        }

        if ($length > 0) {
            return (int) round($weight * 0.3);
        }

        return 0;
    }

    private function resolveRecommendation(int $score, JobCriteria $criteria): ScoreRecommendation
    {
        if ($score >= $criteria->threshold_shortlist) {
            return ScoreRecommendation::SHORTLIST;
        }

        if ($score >= $criteria->threshold_reject) {
            return ScoreRecommendation::REVIEW;
        }

        return ScoreRecommendation::REJECT;
    }
}
