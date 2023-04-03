<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\CoparticipantDiscontinued;
use App\Notifications\PatientInvitation;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;
use App\Surveys\Action\DiscontinuePair;
use App\Surveys\Therapist\DiscontinuationSurvey;

class MakeDiscontinuationSurvey extends SurveyAction
{
    public function execute(Survey $survey)
    {
        app()->make(IncrementParticipantWeek::class)->execute($survey);
        $survey->refresh();
        if ( ! is_null($survey->data('TDS'))) {
            $data = collect(DiscontinuationSurvey::nullData())->map(fn ($item, $key) => $survey->data($key))->toArray();

            $discontinuation = $survey->replicate()->fill([
                'type' => 'discontinuation',
                'category' => 'discontinuation',
                'week' => null,
                'data' => $data
            ]);

            $discontinuation->save();

            app()->make(DiscontinuePair::class)->execute($discontinuation);
        }

    }
}
