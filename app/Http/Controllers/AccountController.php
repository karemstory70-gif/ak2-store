<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function show(): View
    {
        return view('pages.account');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $emailChanged = $validated['email'] !== $user->email;

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return back()->with(
                'success',
                'Profile updated. Please verify your new email address.'
            );
        }

        return back()->with(
            'success',
            'Personal information updated successfully.'
        );
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => [
                'required',
                File::image()
                    ->types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max(2 * 1024),
            ],
        ]);

        $user = $request->user();

        $newAvatarPath = $request->file('avatar')
            ->store('avatars', 'public');

        if (! $newAvatarPath) {
            return back()->withErrors([
                'avatar' => 'Unable to upload the image. Please try again.',
            ]);
        }

        $oldAvatarPath = $user->avatar_path;

        $user->avatar_path = $newAvatarPath;
        $user->save();

        if ($oldAvatarPath) {
            Storage::disk('public')->delete($oldAvatarPath);
        }

        return back()->with(
            'success',
            'Profile photo updated successfully.'
        );
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);

            $user->avatar_path = null;
            $user->save();
        }

        return back()->with(
            'success',
            'Profile photo removed successfully.'
        );
    }
}
