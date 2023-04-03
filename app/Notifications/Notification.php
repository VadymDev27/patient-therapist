<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\HtmlString;

class Notification extends BaseNotification
{
    protected function homeLinkHtml(): string
    {
        return '<a href="'.url('/').'">TOPDDStudy.net</a>';
    }

    public function via($notifiable)
    {
        if (method_exists($notifiable, 'isTest')) {
            return $notifiable->isTest() ? ['database'] : ['mail'];
        }
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage);
    }

    public function toArray($notifiable)
    {
        return $this->toMail($notifiable)->toArray();
    }

}
