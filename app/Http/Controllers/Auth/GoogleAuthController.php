<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    /**
     * Send the user to Google's consent screen.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google's callback. This is BOTH sign-in and registration:
     * - google_id already on file           -> log that account in
     * - no google_id, but email matches an
     *   existing (staff-created) account    -> link it, then log in
     * - no match at all                     -> brand-new self-registered
     *                                          student account
     *
     * New accounts are always created as students. Staff/instructor/admin
     * accounts stay staff-created — Google sign-in only ever *links* to
     * one of those if the Google-verified email happens to match, it
     * never creates one.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException|\Exception $e) {
            return redirect()->route('login')
                ->with('status', 'Google sign-in was cancelled or failed. Please try again.');
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')
                ->with('status', 'Your Google account has no email we can use. Please try a different sign-in method.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Existing account (created by staff, or previously
                // registered with a password) — link it to this Google
                // account rather than making a duplicate.
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    // Google-avatar URL — the `image` column already
                    // stores absolute URLs fine (existing views only
                    // special-case the literal 'no-img.jpg' default).
                    'image' => $googleUser->getAvatar() ?: 'no-img.jpg',
                    // Never actually used to sign in (Google is this
                    // account's only login path) — just satisfies the
                    // NOT NULL column.
                    'password' => Hash::make(Str::random(40)),
                    'role' => 'student',
                    'approval_status' => 'approved',
                    'email_verified_at' => now(),
                ]);

                event(new Registered($user));
            }
        }

        Auth::login($user, remember: true);

        return match ($user->role) {
            'student' => redirect()->intended(route('student.dashboard', absolute: false))
                ->with('success', 'Welcome, ' . $user->name . '!'),
            'instructor' => redirect()->intended(route('instructor.dashboard', absolute: false)),
            'staff' => redirect()->intended(route('staff.dashboard', absolute: false)),
            'intern' => redirect()->intended(route('intern.dashboard', absolute: false)),
            default => redirect()->route('login')
                ->with('status', 'Your account role is not recognized. Please contact an administrator.'),
        };
    }
}
