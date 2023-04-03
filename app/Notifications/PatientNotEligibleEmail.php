<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PatientNotEligibleEmail extends Notification
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     *
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting(' ')
                    ->line('We are  sorry to have to share that your patient did not meet study eligibility requirements.')
                    ->line('If you have another patient you believe would be a good fit for this study, please talk about the study with them.  If they are interested, please return to this site to complete the screening process for them.  (The site will not ask you to re-enter information about yourself; it will only ask you to complete the questions about the patient you are nominating.')
                    ->line('Thank you for your interest in the TOP DD Network RCT!')
                    ->salutation('The TOP DD Research Team');
    }
}
