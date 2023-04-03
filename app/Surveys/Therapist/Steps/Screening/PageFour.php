<?php

namespace App\Surveys\Therapist\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;


class PageFour extends SurveyStep
{
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part IV';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.screening.page-four';

    protected static function fields(): array
    {
        return [
                Field::make('TSS_PtHx_1_Years')->required(),
                Field::make('TSS_PtHx_1_Months')->required(),
                Field::make('TSS_PtHx_2_Years')->required(),
                Field::make('TSS_PtHx_2_Months')->required(),
                Field::make('TSS_PtHx_3')->withHiddenOther()
                    ->radio([
                        'Private practice',
                        'Outpatient clinic',
                        'School',
                        'Forensic',
                        'Hospital inpatient / partial program',
                        'Residential facility',
                        'Other'
                    ]),
                Field::make('TSS_PtHx_4a')
                    ->conditionalFields(appendStringToEach(['4b','4c','4d'], 'TSS_PtHx_'), 'Yes'),
                Field::make('TSS_PtHx_4b'),
                Field::make('TSS_PtHx_4c'),
                Field::make('TSS_PtHx_5a')
                    ->conditionalFields(appendStringToEach(['5b','5c'], 'TSS_PtHx_'), 'Yes'),
                Field::make('TSS_PtHx_5b')->radio([
                    '1' => 'minor / superficial',
                    '2' => '',
                    '3' => '',
                    '4' => '',
                    '5' => 'significant injury'
                ]),
                Field::make('TSS_PtHx_5c'),
                Field::make('TSS_PtHx_6a')
                    ->conditionalFields(appendStringToEach(['6b','6c','6d'], 'TSS_PtHx_'), 'Yes'),
                Field::make('TSS_PtHx_6b'),
                Field::make('TSS_PtHx_6c'),
                Field::make('TSS_PtHx_6d'),

        ];
    }
}
