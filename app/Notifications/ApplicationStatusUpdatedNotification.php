<?php

namespace App\Notifications;

use App\Enums\JobType;
use App\Models\Candidate;
use App\Models\Job;
use App\Notifications\Concerns\UsesNotificationLocale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification
{
    use Queueable;
    use UsesNotificationLocale;

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
        $this->withNotificationLocale();

        return (new MailMessage)
            ->subject(__('emails.status_updated.subject', ['status' => $this->statusLabel]))
            ->view('emails.candidate.job-application-mail', [
                'pageTitle' => __('emails.status_updated.page_title'),
                'heading' => __('emails.status_updated.heading'),
                'variant' => 'status_update',
                'candidate' => $this->candidate,
                'job' => $this->job,
                'statusLabel' => $this->statusLabel,
                'jobTypeLabel' => JobType::tryFrom($this->job->type)?->getLabel() ?? $this->job->type,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
