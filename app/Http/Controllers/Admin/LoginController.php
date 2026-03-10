<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    $email = $request->email;
    $password = $request->password;

    // dd($request->all());

    if (Auth::guard('admin')->attempt([
      'email' => $email,
      'password' => $password
    ])) {
      var_dump(Auth::guard('admin')->user());
      die;
      // return redirect()->route('admin.dashboard');
    } else {
      var_dump(Auth::guard('admin')->user());
      die;
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
