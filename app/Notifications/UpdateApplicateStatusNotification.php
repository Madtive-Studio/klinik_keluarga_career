<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateApplicateStatusNotification extends Notification
{
    use Queueable;

    public $view;
    public $candidate;
    public $job;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($view, $candidate, $job, $status)
    {
        $this->view = $view;
        $this->candidate = $candidate;
        $this->job = $job;
        $this->status = $status;
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
            ->subject('Update Status Lamaran: ' . $this->status)
            ->view($this->view, [
                'candidate' => $this->candidate,
                'job' => $this->job,
                'status' => $this->status,
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
