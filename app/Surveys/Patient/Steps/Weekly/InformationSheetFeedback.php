<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class InformationSheetFeedback extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Information Sheet Feedback';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.info-sheet-feedback';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return appendStringToEach(range(1,3), 'PISF_');
    }

    protected static function fields(): array
    {
        return [
            Field::make('ISF_1')->radio($options = [
                'True', 'More true than not', 'Neutral', 'More false than not', 'False'
            ]),
            Field::make('ISF_2')->radio($options),
            Field::make('ISF_3')->optional(),
        ];
    }
}
