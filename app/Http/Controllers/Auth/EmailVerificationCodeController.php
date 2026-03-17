<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\EmailVerificationCode;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerificationCodeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.digits' => 'Kode verifikasi harus 6 digit angka.',
        ]);

        if (! EmailVerificationCode::matches($user, $validated['code'])) {
            $errorMessage = EmailVerificationCode::isExpired($user)
                ? 'Kode verifikasi sudah kedaluwarsa. Kirim ulang kode baru.'
                : 'Kode verifikasi tidak sesuai. Coba lagi.';

            throw ValidationException::withMessages([
                'code' => $errorMessage,
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        EmailVerificationCode::clear($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi.');
    }
}
