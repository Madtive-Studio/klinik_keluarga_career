<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\EducationLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Candidate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $candidate = Candidate::with('profile')
            ->findOrFail(Auth::guard('candidate')->id());

        return view('candidate.profile.edit', [
            'candidate' => $candidate,
            'educationLevels' => EducationLevel::cases(),
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $candidate = Candidate::findOrFail(Auth::guard('candidate')->id());

        DB::transaction(function () use ($request, $candidate) {
            $profileData = $request->safe()->only([
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
            ]);

            $candidate->profile()->updateOrCreate(
                ['candidate_id' => $candidate->id],
                $profileData
            );
        });

        return redirect()
            ->route('candidate.my.profile.edit')
            ->with('success', __('messages.profile.saved'));
    }
}
