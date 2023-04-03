<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SurveyCompleted;
use App\Notifications\WelcomeEmail;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(SurveyCompleted $event)
    {
        $survey = $event->survey;
        $user = $survey->user;
        if ( !$user->is_therapist && !is_null($user->pair) && ($survey->is_eligible))
        {
            $user->notify(new WelcomeEmail);
            $user->getCoparticipant()->notify(new WelcomeEmail);
        }
    }
}
