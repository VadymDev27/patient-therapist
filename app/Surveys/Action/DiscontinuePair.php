<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\CoparticipantDiscontinued;
use App\Notifications\PatientInvitation;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;

class DiscontinuePair extends SurveyAction
{
    public function execute(Survey $survey)
    {
        $user = $survey->user;
        $user->pair->discontinue();
        $user->getCoParticipant()->notify(new CoparticipantDiscontinued);
    }
}
