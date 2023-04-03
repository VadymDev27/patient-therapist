<?php

namespace App\Surveys\Therapist\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class PracticeExercisesFeedback extends SurveyStep
{
    function title(): string
    {
        return 'Practice Exercises Feedback';
    }
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.weekly.feedback';

    protected static function fields(): array
    {
        return [
            Field::make('TPF_1')
                ->radio($options =
                    ['Very true', 'True', 'More true than not', 'Neutral', 'More false than not', 'False'])
                ->question("This practice exercises addressed topics relevant to my patient."),
            Field::make('TPF_2')
                ->radio($options)
                ->question("I understood the information presented in the practice exercises."),
            Field::make('TPF_3')
                ->question("Optional: Other feedback about the practice exercises?")
                ->optional()
        ];
    }
}
