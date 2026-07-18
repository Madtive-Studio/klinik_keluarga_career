<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'user' => User::findOrFail(Auth::guard('admin')->id()),
        ]);
    }

    public function update(AdminProfileRequest $request): RedirectResponse
    {
        $user = User::findOrFail(Auth::guard('admin')->id());

        $data = $request->safe()->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $user->update($data);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', __('messages.admin.profile.updated'));
    }
}
