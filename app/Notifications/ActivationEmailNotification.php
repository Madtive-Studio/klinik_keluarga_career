<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationEmailNotification extends Notification
{
    use Queueable;

    public $candidate;
    public $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($candidate, $verificationUrl)
    {
        $this->candidate = $candidate;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Verifikasi Email Kamu')
                    ->view('candidate.auth.email-verification', [
                        'candidate' => $this->candidate,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
