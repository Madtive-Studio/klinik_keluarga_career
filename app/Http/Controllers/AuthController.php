<?php

namespace App\Http\Controllers;

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
        return view('client.auth.login');
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

        if (Auth::guard('candidate')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $candidate = Candidate::where('email', $request->email)->whereNotNull('email_verified_at')->first();
            if (!$candidate) {
                return redirect()->back()->with('error', 'Email kamu belum di verifikasi');
            }

            return redirect()->route('client.home');

        } else {
            return redirect()->back()->with('error', 'Email atau password salah!');
        }
    }

    public function register()
    {
        return view('client.auth.register');
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
        
        $request['password'] = bcrypt($request->password);
        $request['verification_token'] = Str::random(64);
        $candidate = Candidate::create($request->all());

        $verificationUrl = route('client.email-verification', ['token' => $candidate->verification_token]);
        $candidate->notify(new ActivationEmailNotification($candidate, $verificationUrl));

        return redirect()->back()->with('success', 'Register berhasil, silahkan lihat email kamu untuk verifikasi');
    }

    public function verification($token)
    {
        $candidate = Candidate::where('verification_token', $token)->first();
        if ($candidate) {
            $candidate->update([
                'email_verified_at' => now(),
                'verification_token' => null
            ]);
    
            return view('client.auth.success_verification');
        }

        return redirect()->route('client.login')->with('success', 'Email kamu sudah di verifikasi');
    }

    public function logout()
    {
        $user = auth()->guard('candidate')->user();
        if ($user) {
            Auth::logout();
        }

        return redirect()->route('client.home');
    }
}
