<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class WelcomeEmail extends Notification
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
        $corole = $notifiable->getCoParticipantName();
        return (new MailMessage)
            ->greeting('Welcome to the TOP DD Network Randomized Control Trial (RCT)!')
            ->line("We are excited to welcome you and your $corole to the study.  (Your $corole will be receiving an email letting them know this as well.)")
            ->line(new HtmlString(
                'Please click the "Access Initial Survey" button below (or go to '.$this->homeLinkHtml().') to complete the Initial Survey.'
            ))
            ->action('Access Initial Survey', $notifiable->getSurveyUrl('initial'))
            ->line("After you and your $corole have each completed the survey, you will receive another email letting you know if you have been randomized to gain immediate access to the study materials or will be on the 6-month waitlist.")
            ->line("Thank you for participating in the TOP DD Network RCT!")
            ->salutation('The TOP DD Research Team');
    }
}
