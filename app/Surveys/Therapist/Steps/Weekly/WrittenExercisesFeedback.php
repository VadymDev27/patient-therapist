<?php

namespace App\Surveys\Therapist\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class WrittenExercisesFeedback extends SurveyStep
{
    function title(): string
    {
        return 'Written Exercises Feedback';
    }
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.weekly.feedback';

    protected static function fields(): array
    {
        return [
            Field::make('TWF_1')
                ->radio($options = [
                    'Very true', 'True', 'More true than not', 'Neutral', 'More false than not', 'False'
                ])->question("This written exercises addressed topics relevant to my patient."),
            Field::make('TWF_2')
                ->radio($options)
                ->question("I understood the information presented in the written exercises"),
            Field::make('TWF_3')
                ->question("Optional: Other feedback about the written exercises?")
                ->optional()
        ];
    }
}
