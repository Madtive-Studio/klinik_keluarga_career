<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewInvitationNotification extends Notification
{
    use Queueable;

    public $candidate;
    public $job;
    public $interview;
    public $company;

    /**
     * Create a new notification instance.
     */
    public function __construct($candidate, $job, $interview, $company)
    {
        $this->candidate = $candidate;
        $this->job = $job;
        $this->interview = $interview;
        $this->company = $company;
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
            ->subject('Undangan Wawancara')
            ->view('admin.schedule-interviews.email', [
                'candidate' => $this->candidate,
                'job' => $this->job,
                'interview' => $this->interview,
                'company' => $this->company,
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
