<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\PatientInvitation;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;

class IncrementParticipantWeek extends SurveyAction
{
    public function execute(Survey $survey)
    {
        $user = $survey->user;
        $user->incrementWeek();
    }
}
