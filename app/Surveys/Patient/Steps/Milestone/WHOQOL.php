<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class WHOQOL extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Quality of Life Brief Scale';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.whoqol';

    protected static function fields(): array
    {
        return ([
            Field::make('QOL_1')
                ->question('How would you rate your quality of life?')
                ->radio([
                    1 => 'Very poor',
                    2 => 'Poor',
                    3 => 'Neither poor nor good',
                    4 => 'Good',
                    5 => 'Very good'
                ]),
            Field::make('QOL_2')
                ->question('How satisfied are you with your personal relationships?')
                ->radio($satisfactionScale = [
                    1 => 'Very dissatisfied',
                    2 => 'Dissatisfied',
                    3 => 'Neither satisfied nor dissatisfied',
                    4 => 'Satisfied',
                    5 => 'Very satisfied'
                ]),
            Field::make('QOL_3')
                ->question('How satisfied are you with the support you get from your friends?')
                ->radio($satisfactionScale),
            Field::make('QOL_4')
                ->question('How satisfied are you with your sex life?')
                ->radio($satisfactionScale)

        ]);
    }
}
