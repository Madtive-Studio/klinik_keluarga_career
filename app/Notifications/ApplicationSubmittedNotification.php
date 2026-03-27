<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public $candidate;
    public $job;

    /**
     * Create a new notification instance.
     */
    public function __construct($candidate, $job)
    {
        $this->candidate = $candidate;
        $this->job = $job;
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
            ->subject('Lamaran Kamu Berhasil Dikirim')
            ->view('emails.candidate.job-application-mail', [
                'pageTitle' => 'Lamaran Kamu Berhasil Dikirim',
                'heading' => 'Lamaran Kamu Berhasil Dikirim',
                'variant' => 'application_submitted',
                'candidate' => $this->candidate,
                'job' => $this->job,
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
