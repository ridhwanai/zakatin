<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationCodeNotification;
use App\Support\EmailVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        $verificationCode = EmailVerificationCode::issue($request->user());

        try {
            $request->user()->notify(new EmailVerificationCodeNotification(
                $verificationCode,
                EmailVerificationCode::EXPIRY_MINUTES
            ));
        } catch (\Throwable) {
            return back()->with('error', 'Pengiriman kode verifikasi gagal. Coba lagi.');
        }

        return back()->with('status', 'verification-code-sent');
    }
}
