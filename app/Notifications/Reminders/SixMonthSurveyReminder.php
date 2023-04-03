<?php

namespace App\Notifications\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Notification;

class SixMonthSurveyReminder extends Notification
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
                    ->line('Thank you for registering for the TOP DD Randomized Controlled Trial. You can do 6-month survye now')
                    ->action('Access 6-month survey survey', url('/login'))
                    ->line('Thank you for participating in the TOP DD RCT!');
    }

}
