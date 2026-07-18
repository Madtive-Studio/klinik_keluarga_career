<?php

namespace App\Notifications;

use App\Notifications\Concerns\UsesNotificationLocale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationEmailNotification extends Notification
{
    use Queueable;
    use UsesNotificationLocale;

    public function __construct(
        public $candidate,
        public $verificationUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->withNotificationLocale();

        return (new MailMessage)
            ->subject(__('emails.activation.subject'))
            ->view('candidate.auth.email-verification', [
                'candidate' => $this->candidate,
                'verificationUrl' => $this->verificationUrl,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
