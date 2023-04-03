<?php

namespace App\Notifications\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Notification;

class Initial2SurveyReminder extends Notification
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
                    ->line('Thank you for registering for the TOP DD Randomized Controlled Trial. It has now been 6 months since you were randomized into the waitlist group, so you may now access the study materials.')
                    ->line('Please click the link below to access the initial survey for the study. After you have both completed the survey, you will receive another email letting you know if you have been randomized to gain immediate access to the study materials or if you will be on the 6-month waitlist.')
                    ->action('Access initial survey', url('/login'))
                    ->line('Thank you for participating in the TOP DD RCT!');
    }
}
