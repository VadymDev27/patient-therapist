<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class WrittenExercisesFeedback extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.written-feedback';

    protected static function fields(): array
    {
        return [
            Field::make('WEF_1')->radio(['I did them, they helped a lot.', 'I did them, they helped somewhat', 'I did them, but they didn\'t seem to help', 'I didn\'t do them.']),
            Field::make('WEF_2')->optional()
        ];
    }
}
