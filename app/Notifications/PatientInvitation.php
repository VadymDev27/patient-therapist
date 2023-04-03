<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class PatientInvitation extends Notification
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
        $url = $this->getScreeningUrl($notifiable);

        return (new MailMessage)
                    ->greeting(' ')
                    ->line('Please forward this email to your patient.')
                    ->line(new HtmlString('<hr>'))
                    ->line('# Welcome to the screening process for the TOP DD Network Randomized Control Trial (RCT)!')
                    ->line(new HtmlString(
                        "Your therapist has indicated that you are interested in participating in the TOP DD Network RCT, a study that provides participants with access to information about ways to help yourself reduce emotional overwhelm (“feeling too much”), PTSD symptoms, and dissociation.  (For more information, go to {$this->homeLinkHtml()})."
                    ))
                    ->line('To confirm your interest in the study, and to complete the eligibility screening process, please click the button below.')
                    ->action('Create an account', $url)
                    ->line('Thank you for your interest in our study!')
                    ->salutation('The TOP DD Research Team');
    }

    /**
     * Get the signed URL for the participant screening.
     *
     * @param mixed $notifiable
     * @return string
     */
    public function getScreeningUrl($notifiable)
    {
        return URL::signedRoute('register.patient.form', ['therapist' => $notifiable->id]);
    }
}
