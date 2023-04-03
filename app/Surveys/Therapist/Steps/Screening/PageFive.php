<?php

namespace App\Surveys\Therapist\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;


class PageFive extends SurveyStep
{
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part V';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.screening.page-five';

    protected static function fields(): array
    {
        return array_merge(
            [
                Field::make('TSS_DevHx_1')->radio([
                    '1' => 'poor',
                    '2' => 'working class',
                    '3' => 'middle class',
                    '4' => 'upper middle class',
                    '5' => 'upper class'
                ]),
            ],
            array_map(
                fn (string $name) => Field::make($name)->radio(['No', 'Unclear', 'Yes']),
                appendStringToEach(range(2,6), 'TSS_DevHx_')
            ),
            [
                Field::make('TSS_AdultHx_1')->radio(['No', 'Once', '1-5 times', 'More than 5 times']),
                Field::make('TSS_AdultHx_2a')
                    ->conditionalFields('TSS_AdultHx_2b', 'Yes'),
                Field::make('TSS_AdultHx_2b')->radio($options = ['Victim', 'Perpetrator', 'Both']),
                Field::make('TSS_AdultHx_3a')
                    ->conditionalFields('TSS_AdultHx_3b', 'Yes'),
                Field::make('TSS_AdultHx_3b')->radio($options),
                Field::make('TSS_AdultHx_4a')
                    ->conditionalFields('TSS_AdultHx_4b', 'Yes'),
                Field::make('TSS_AdultHx_4b')->radio($options),
                Field::make('patient_id')->optional(),  // for failed patient screens, to keep the connection
                Field::make('fail_reason')->optional()  // for failed screens
            ]
        );
    }
}
