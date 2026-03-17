<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $expiresInMinutes
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Email Zakatin')
            ->greeting('Assalamu\'alaikum,')
            ->line('Gunakan kode berikut untuk verifikasi email akun Zakatin Anda:')
            ->line('**'.$this->code.'**')
            ->line('Kode berlaku selama '.$this->expiresInMinutes.' menit.')
            ->line('Jangan bagikan kode ini ke siapa pun.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
