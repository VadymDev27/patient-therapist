<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AdminInvitation extends Notification
{
    use Queueable;


    public function __construct(public $token)
    {
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('admin.password.create', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return $this->buildMailMessage($url);
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('You have been added as an admin to TOP DD RCT')
            ->line('You are receiving this email because you have been invited to create an administrator account at the TOP DD website.')
            ->line('Please click the link below to set the password for your account.')
            ->action('Set password', $url)
            ->line('This link will expire in 1 week. If it has expired, please contact a TOP DD RCT administrator to re-send.');
    }

}
