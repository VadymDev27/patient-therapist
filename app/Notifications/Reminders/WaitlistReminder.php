<?php

namespace App\Notifications\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Notification;

class WaitlistReminder extends Notification
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
                    ->greeting('Reminder: Waitlist for TOP DD Randomized Controlled Trial!')
                    ->line("Thank you for registering for the TOP DD Randomized Controlled Trial. You are still on the waitlist and you will gain access to the study materials on {$notifiable->randomizationDate()->addMonths(6)->toFormattedDateString()}.")
                    ->line('Thank you for participating in the TOP DD RCT!');
    }

}
