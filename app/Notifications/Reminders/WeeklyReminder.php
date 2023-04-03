<?php

namespace App\Notifications\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Notification;

class WeeklyReminder extends Notification
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Welcome to the TOP DD Randomized Controlled Trial!')
                    ->line('You can access weekly content now')
                    ->action('Access weekly content', url('/login'))
                    ->line('Thank you for participating in the TOP DD RCT!');
    }
}
