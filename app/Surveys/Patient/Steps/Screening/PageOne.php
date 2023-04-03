<?php

namespace App\Surveys\Patient\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;


class PageOne extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Part I';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.screening.page-one';

    protected static function fields(): array
    {
        return ([
            Field::make('PSS_Demographics_1'),
            Field::make('PSS_Demographics_2')
                ->withHiddenOther()
                ->multiSelect(['Woman', 'Man', 'Nonbinary', 'Trans', 'Other']),
            Field::make('PSS_Demographics_3')
                ->multiSelect(['Caucasian', 'Asian', 'Latino / Hispanic', 'African', 'Native / Aboriginal / Indigenous / First People', 'Other'])
                ->withHiddenOther(),
            Field::make('PSS_Demographics_4')
                ->radio(['Married (first marriage)', 'Married (remarried)','Divorced','Separated','Widowed','In a relationship', 'Not currently in a relationship', 'Never involved with a long-term partner']),
            Field::make('PSS_Demographics_5')
                ->radio(['High school graduate or equivalent', 'Some college/university courses', 'Undergraduate degree','Graduate degree','Trade or technical school', 'Professional or work training e.g. nursing aid, business school'])
                ->conditionalFields('PSS_Demographics_5_GradeNumber', 'Grade')
                ->conditionalFields('PSS_Demographics_5_Other', 'Other:'),
            Field::make('PSS_Demographics_5_GradeNumber'),
            Field::make('PSS_Demographics_5_Other'),
            Field::make('PSS_WorkDisability_1')
                ->multiSelect(['Part-time work','Full-time work','Part-time school','Full-time school','Homemaker','Unemployed']),
            Field::make('PSS_WorkDisability_2')
                ->conditionalFields(
                    appendStringToEach(
                        ['3_Years', '3_Months', '4', '5'],
                        'PSS_WorkDisability_'
                    ),
                    'Yes'
                ),
            Field::make('PSS_WorkDisability_3_Years'),
            Field::make('PSS_WorkDisability_3_Months'),
            Field::make('PSS_WorkDisability_4')->radio(['Medical reasons', 'Psychological reasons','Both']),
            Field::make('PSS_WorkDisability_5')->radio(['Full disability support','Partial disability support'])
        ]);
    }
}
