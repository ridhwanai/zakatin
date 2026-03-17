<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmailVerificationCode
{
    public const EXPIRY_MINUTES = 10;

    public static function issue(User $user): string
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_verification_code' => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ])->save();

        return $code;
    }

    public static function matches(User $user, string $code): bool
    {
        if (
            empty($user->email_verification_code)
            || empty($user->email_verification_code_expires_at)
            || now()->greaterThan($user->email_verification_code_expires_at)
        ) {
            return false;
        }

        return Hash::check($code, $user->email_verification_code);
    }

    public static function isExpired(User $user): bool
    {
        if (empty($user->email_verification_code_expires_at)) {
            return true;
        }

        return now()->greaterThan($user->email_verification_code_expires_at);
    }

    public static function clear(User $user): void
    {
        $user->forceFill([
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ])->save();
    }
}
