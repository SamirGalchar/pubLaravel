<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterUserAdminNotification extends Notification {
    
    use Queueable;
    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details){
        $this->details = $details; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $html = $this->details['html'];
        $subject = $this->details['subject'];
        
        return (new MailMessage)
                ->subject($subject)
                //->replyTo($this->details['email'],$this->details['name'])
                ->markdown('emails.email',compact('html', 'subject'));
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
            //
        ];
    }
}
