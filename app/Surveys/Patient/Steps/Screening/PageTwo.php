<?php

namespace App\Surveys\Patient\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;


class PageTwo extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Part II';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.screening.page-two';

    protected static function fields(): array
    {
        return array_map(
            fn (string $name) => Field::make($name)->required(),
            appendStringToEach(range(1,10), 'PSS_Eligibility_')
        );
    }
}
