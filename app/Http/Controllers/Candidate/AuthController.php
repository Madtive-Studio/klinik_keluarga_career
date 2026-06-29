<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $candidate = Candidate::where('email', $request->email)->first();

        if (!$candidate || !Hash::check($request->password, $candidate->password)) {
            return redirect()->back()->with('error', 'Email atau password salah!');
        }

        if (!$candidate->email_verified_at) {
            return redirect()->back()->with('error', 'Email kamu belum di verifikasi');
        }

        Auth::guard('candidate')->login($candidate, $request->boolean('remember'));

        return redirect()->route('candidate.home');
    }

    public function register()
    {
        return view('candidate.auth.register');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:candidates,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required',
            'phone' => 'required|numeric',
            'birth_date' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $candidate = Candidate::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'verification_token' => Str::random(64),
        ]);

        $verificationUrl = route('candidate.email-verification', ['token' => $candidate->verification_token]);
        $candidate->notify(new ActivationEmailNotification($candidate, $verificationUrl));

        return redirect()->back()->with('success', 'Register berhasil, silahkan lihat email kamu untuk verifikasi');
    }

    public function verification($token)
    {
        $candidate = Candidate::where('verification_token', $token)->first();
        if ($candidate) {
            $candidate->update([
                'email_verified_at' => now(),
                'verification_token' => null,
            ]);

            return view('candidate.auth.success_verification');
        }

        return redirect()->route('candidate.login.form')->with('success', 'Email kamu sudah di verifikasi');
    }

    public function logout()
    {
        Auth::guard('candidate')->logout();

        return redirect()->route('candidate.home');
    }
}
