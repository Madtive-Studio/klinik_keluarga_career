<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\User;
use App\Notifications\ActivationEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('candidate.auth.login');
    }

    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $loginInput = trim($request->input('email', $request->input('login')));
        $password = $request->password;

        $normalizedPhone = normalizePhoneNumber($loginInput);

        $candidate = Candidate::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->orWhere('phone', $loginInput)
            ->when($normalizedPhone, function ($query, $normalizedPhone) {
                $query->orWhere('phone', $normalizedPhone);
            })
            ->first();

        if ($candidate && Hash::check($password, $candidate->password)) {
            if (!$candidate->email_verified_at) {
                return redirect()->back()->with('error', __('messages.auth.email_not_verified'));
            }

            Auth::guard('candidate')->login($candidate);
            return redirect()->route('candidate.home');
        } else {
            return redirect()->back()->with('error', __('messages.auth.invalid_credentials'));
        }
    }

    public function register()
    {
        return view('candidate.auth.register');
    }

    public function verify(Request $request)
    {
        $normalizedPhone = $this->normalizePhoneNumber($request->input('phone'), $request->input('country_code', '+62'));
        $request->merge(['phone' => $normalizedPhone]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:candidates,email',
            'username' => 'required|string|alpha_dash|max:50|unique:candidates,username',
            'password' => 'required|min:8|confirmed',
            'name' => 'required',
            'phone' => 'required|numeric|digits_between:9,15',
            'birth_date' => 'required|date|before_or_equal:today',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        
        $candidateData = $request->only(['name', 'email', 'username', 'phone', 'birth_date', 'address']);
        $candidateData['password'] = bcrypt($request->password);
        $candidateData['verification_token'] = Str::random(64);

        $candidate = Candidate::create($candidateData);

        $verificationUrl = route('candidate.email-verification', [
            'token' => $candidate->verification_token,
            'email' => $candidate->email,
        ]);
        $candidate->notify(new ActivationEmailNotification($candidate, $verificationUrl));

        return redirect()->back()->with('success', __('messages.auth.register_success'));
    }

    private function normalizePhoneNumber(?string $phone, ?string $countryCode = '+62'): ?string
    {
        return normalizePhoneNumber($phone, $countryCode);
    }

    public function verification(Request $request, $token)
    {
        $candidate = Candidate::where('verification_token', $token)->first();

        if ($candidate) {
            if ($candidate->email_verified_at) {
                return redirect()->route('candidate.login.form')->with('info', __('messages.auth.already_verified'));
            }

            $candidate->update([
                'email_verified_at' => now(),
            ]);

            return view('candidate.auth.success-verification');
        }

        $email = $request->query('email');
        if ($email) {
            $candidateByEmail = Candidate::where('email', $email)->first();
            if ($candidateByEmail && $candidateByEmail->email_verified_at) {
                return redirect()->route('candidate.login.form')->with('info', __('messages.auth.already_verified'));
            }
        }

        return redirect()->route('candidate.login.form')->with('error', __('messages.auth.invalid_verification_token'));
    }

    public function logout()
    {
        $user = auth()->guard('candidate')->user();
        if ($user) {
            Auth::logout();
        }

        return redirect()->route('candidate.home');
    }
}
