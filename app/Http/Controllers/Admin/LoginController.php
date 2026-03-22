<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function login()
  {
    return view('admin.auth.login');
  }

  public function process(Request $request)
  {
    $credentials = [
        'email' => $request->email,
        'password' => $request->password
    ];

    if (Auth::guard('admin')->attempt($credentials)) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->back()->with('error', 'Email atau password salah!');
    }
  }

  public function logout()
  {
    $user = auth()->guard('admin')->user();
    if ($user) {
      Auth::logout();
    }

    return redirect()->route('admin.login');
  }
}
