<?php

namespace App\Notifications;

use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Candidate $candidate,
        public Job $job,
        public string $statusLabel,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update Status Lamaran: ' . $this->statusLabel)
            ->view('emails.candidate.job-application-mail', [
                'pageTitle' => 'Update Status Lamaran',
                'heading' => 'Status Lamaran Diperbarui',
                'variant' => 'status_update',
                'candidate' => $this->candidate,
                'job' => $this->job,
                'statusLabel' => $this->statusLabel,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
