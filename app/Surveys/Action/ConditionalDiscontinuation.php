<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\CoparticipantDiscontinued;
use App\Notifications\PatientInvitation;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;

class ConditionalDiscontinuation extends SurveyAction
{
    public function execute(Survey $survey)
    {
        if ($survey->data('CWR_1') === 'No') {
            app()->make(DiscontinuePair::class)->execute($survey);
        }
    }
}
