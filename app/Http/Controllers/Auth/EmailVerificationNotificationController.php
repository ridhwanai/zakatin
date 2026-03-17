<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationCodeNotification;
use App\Support\EmailVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $verificationCode = EmailVerificationCode::issue($request->user());

            $request->user()->notify(new EmailVerificationCodeNotification(
                $verificationCode,
                EmailVerificationCode::EXPIRY_MINUTES
            ));
        } catch (\Throwable $th) {
            Log::error('Failed to issue or resend verification code.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'error' => $th->getMessage(),
            ]);

            return back()->with('error', 'Pengiriman kode verifikasi gagal. Cek konfigurasi email/database lalu coba lagi.');
        }

        return back()->with('status', 'verification-code-sent');
    }
}
