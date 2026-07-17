<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Breeze's Profile controller, trimmed to Update Info + Update Password
 * (see routes/auth.php PasswordController for the password half). The
 * "Delete Account" self-service action from stock Breeze was removed —
 * account lifecycle here is admin-controlled via Staff Accounts.
 */
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $request->user()->save();

        return back()->with('success', 'Profile updated.');
    }
}
