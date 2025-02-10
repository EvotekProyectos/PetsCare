<?php

namespace App\Notifications;

use App\Models\GroomingStatusHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroomingStatus extends Notification
{
    use Queueable;

    protected $groomingStatusHistory;

    /**
     * Create a new notification instance.
     */
    public function __construct(GroomingStatusHistory $groomingStatusHistory)
    {
        $this->groomingStatusHistory = $groomingStatusHistory;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

    public function toDatabase($notifiable)
    {
        return [
            'reception_id' => $this->groomingStatusHistory->reception_id,
            'reception_type_id' => $this->groomingStatusHistory->reception->receptionType->id,
            'grooming_status_id' => $this->groomingStatusHistory->grooming_status_id,
            'pet' => $this->groomingStatusHistory->reception->pet->name,
            'status' => $this->groomingStatusHistory->groomingStatus->name, 
            'type' => $this->groomingStatusHistory->reception->receptionType->name, 
            'grooming_status_id' => $this->groomingStatusHistory->grooming_status_id,
            'phone' => $this->groomingStatusHistory->reception->family->phone,
        ];
    }
}
