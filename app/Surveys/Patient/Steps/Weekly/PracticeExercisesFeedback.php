<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class PracticeExercisesFeedback extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.practice-feedback';

    protected static function fields(): array
    {
        return [
            Field::make('PEF_1')
                ->radio(['I did them, they helped a lot.', 'I did them, they helped somewhat', 'I did them, but they didn\'t seem to help', 'I didn\'t do them.']),
            Field::make('PEF_2')->optional()
        ];
    }
}
