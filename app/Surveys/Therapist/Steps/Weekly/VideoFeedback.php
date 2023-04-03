<?php

namespace App\Surveys\Therapist\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class VideoFeedback extends SurveyStep
{
    function title(): string
    {
        return 'Video Feedback';
    }
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.weekly.feedback';

    protected static function fields(): array
    {
        return [
            Field::make('TVF_1')
                ->radio($options = [
                    'Very true', 'True', 'More true than not', 'Neutral', 'More false than not', 'False'])
                ->question("This video addressed topics relevant to my patient."),
            Field::make('TVF_2')
                ->radio($options)
                ->question("This video was easy to understand."),
            Field::make('TVF_3')
                ->question("Optional: Other feedback about the video?")
                ->optional()
        ];
    }
}
