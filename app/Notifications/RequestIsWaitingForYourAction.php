<?php

namespace App\Notifications;

use App\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestIsWaitingForYourAction extends Notification
{
    use Queueable;

    public $idea;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["database"];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("iChange ".$this->idea->getReqIdAsString().' - Request is waiting for your action')
            ->line('An iChange request is waiting for your action.')
            ->action('Open Request', route("ideas.display", $this->idea->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "message" => 'Request is waiting for your action.',
            "requestNumber" => $this->idea->getReqIdAsString(),
            "idea" => $this->idea
        ];
    }
}
