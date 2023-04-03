<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;
use App\Surveys\Patient\Steps\Screening\PageTwo;

class ComputePatientEligibility extends SurveyAction
{
    public function execute(Survey $survey)
    {
        $patient = $survey->user;
        $patient->is_eligible = $this->computeEligibility($survey->data);
        $patient->save();

        if ($patient->is_eligible)
        {
            $patient->notify(new WelcomeEmail);
            $patient->getCoParticipant()->notify(new WelcomeEmail);
        } else {
            $therapist = $patient->getCoParticipant();
            $therapist->setScreenResult(false, 'patient-fail', $patient->id);

            $therapist->pair_id = null;
            $therapist->save();

            $patient->pair_id = null;
            $patient->save();

            $therapist->notify(new PatientNotEligibleEmail);

        }
    }

    private function computeEligibility(array $data)
    {
        $questions = collect(PageTwo::fieldNames());

        return $questions->every(function ($value) use ($data) {
            return data_get($data, $value) === 'Yes';
        });
    }
}
