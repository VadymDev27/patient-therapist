<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class ProgramDevelopment extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.program-development';

    protected static function fields(): array
    {
        return [
            Field::make('PPD_1')->question('We are exploring the possiblity of developing additional online education programs. Are there any other education programs that you’d like us to create?'),
            Field::make('PPD_2')
                ->radio([
                    'at the same pace as this program (i.e., 1 week between topics to allow for practice and facilitate deeper learning)',
                    'faster than this program',
                    'slower than this program'
                ])->question("Pace: If we offer one or more of these programs, at which pace should they be taught? ")
        ];
    }
}
