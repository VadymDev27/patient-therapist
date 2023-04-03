<?php

namespace App\Listeners;

use App\Events\SurveyCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PatientInvitation;


class SendPatientInvitation
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(SurveyCompleted $event)
    {
        $survey = $event->survey;
        $user = $survey->user;
        if ($survey->is_eligible && $user->is_therapist) {
            $user->notify(new PatientInvitation());
        }
    }
}
