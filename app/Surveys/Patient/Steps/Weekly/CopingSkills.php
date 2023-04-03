<?php

namespace App\Surveys\Patient\Steps\Weekly;

use Surveys\Field;
use Surveys\SurveyStep;


class CopingSkills extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Coping Skills';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.weekly.coping-skills';

    protected static function fields(): array
    {
        return array_map(
            fn (string $name) => Field::make($name)->radio([
                '0%' => 'never true',
                10 => '',
                20 => '',
                30 => '',
                40 => '',
                50 => '',
                60 => '',
                70 => '',
                80 => '',
                90 => '',
                '100%' => 'always true'
            ]),
            appendStringToEach(range(1,4), 'CSET_')
        );
    }
}
