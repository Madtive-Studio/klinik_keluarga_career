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
    $loginInput = trim($request->input('email', $request->input('login')));
    $password = $request->input('password');

    $user = User::where('email', $loginInput)
        ->orWhere('username', $loginInput)
        ->first();

    if ($user && Hash::check($password, $user->password)) {
        Auth::guard('admin')->login($user);
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->back()->with('error', __('messages.admin.auth.invalid_credentials'));
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
