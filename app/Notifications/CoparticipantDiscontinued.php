<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CoparticipantDiscontinued extends Notification
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
                    ->greeting('TOP DD Randomized Controlled Trial: Your coparticipant has discontinued')
                    ->line("Thank you for participating in the TOPDD Randomized Controlled Trial. Your {$notifiable->getCoParticipant()->role} has discontinued the study and therefore you have been automatically discontinued as well.")
                    ->line('We would appreciate it if you would fill out the Discontinuation Survey so that we can get more information.')
                    ->action('Access discontinuation survey', $notifiable->getSurveyUrl('discontinuation'))
                    ->line('Thank you for participating in the TOP DD RCT!');
    }

}
