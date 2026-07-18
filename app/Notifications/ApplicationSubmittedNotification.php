<?php

namespace App\Notifications;

use App\Enums\JobType;
use App\Notifications\Concerns\UsesNotificationLocale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification
{
    use Queueable;
    use UsesNotificationLocale;

    public function __construct(
        public $candidate,
        public $job,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->withNotificationLocale();

        return (new MailMessage)
            ->subject(__('emails.application_submitted.subject'))
            ->view('emails.candidate.job-application-mail', [
                'pageTitle' => __('emails.application_submitted.heading'),
                'heading' => __('emails.application_submitted.heading'),
                'variant' => 'application_submitted',
                'candidate' => $this->candidate,
                'job' => $this->job,
                'jobTypeLabel' => JobType::tryFrom($this->job->type)?->getLabel() ?? $this->job->type,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
