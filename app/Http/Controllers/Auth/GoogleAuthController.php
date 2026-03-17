<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return $this->googleDriver()->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = $this->googleDriver()->user();
        } catch (\Throwable $th) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Login Google gagal. Coba lagi.']);
        }

        $email = $googleUser->getEmail();

        if ($email === null) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Akun Google tidak mengirim email.']);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $user->fill([
                'google_id' => $googleUser->getId(),
                'name' => $user->name ?: ($googleUser->getName() ?? 'User Google'),
                'avatar_url' => $googleUser->getAvatar() ?: $user->avatar_url,
            ]);

            if (! $user->hasVerifiedEmail()) {
                $user->email_verified_at = now();
            }

            $user->email_verification_code = null;
            $user->email_verification_code_expires_at = null;

            $user->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?? 'User Google',
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'email_verification_code' => null,
                'email_verification_code_expires_at' => null,
                'password' => Hash::make(Str::password(32)),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function googleDriver()
    {
        $redirectUrl = config('services.google.redirect') ?: route('auth.google.callback');

        return Socialite::driver('google')->redirectUrl($redirectUrl);
    }
}
