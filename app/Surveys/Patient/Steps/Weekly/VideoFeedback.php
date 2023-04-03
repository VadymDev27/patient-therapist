<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class VideoFeedback extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.video-feedback';

    protected static function fields(): array
    {
        return [
            Field::make('PVF_1')->radio($options = ['True', 'More true than not', 'Neutral', 'More false than not', 'False']),
            Field::make('PVF_2')->radio($options),
            Field::make('PVF_3')->radio($options),
            Field::make('PVF_4')->optional(),
        ];
    }
}
