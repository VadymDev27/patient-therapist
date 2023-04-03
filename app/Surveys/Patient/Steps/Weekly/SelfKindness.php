<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class SelfKindness extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.self-kindness';


    protected static function fields(): array
    {
        return [
            Field::make('SK_1')->radio($options = [
                1 => 'almost never',
                2 => '',
                3 => '',
                4 => '',
                5 => 'almost always'
            ]),
            Field::make('SK_2')->radio($options)
        ];
    }
}
