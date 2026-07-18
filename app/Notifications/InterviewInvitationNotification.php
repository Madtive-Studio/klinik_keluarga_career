<?php

namespace App\Notifications;

use App\Notifications\Concerns\UsesNotificationLocale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewInvitationNotification extends Notification
{
    use Queueable;
    use UsesNotificationLocale;

    public function __construct(
        public $candidate,
        public $job,
        public $interview,
        public $company,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->withNotificationLocale();

        return (new MailMessage)
            ->subject(__('emails.interview.subject'))
            ->view('admin.schedule-interviews.email', [
                'candidate' => $this->candidate,
                'job' => $this->job,
                'interview' => $this->interview,
                'company' => $this->company,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
