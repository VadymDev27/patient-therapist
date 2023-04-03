<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class ProgramDevelopment extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.program-development';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return appendStringToEach(range(1,3), 'TPD_');
    }

    protected static function fields(): array
    {
        return [
            Field::make('PD_1')
                ->multiSelect([
                    'PTSD symptom management',
                    'Improving internal awareness, communication, and cooperation between dissociative self-states',
                    'Healing shame',
                    'Creating and maintaining healthy relationships (including between patient and therapist)',
                    'None of the above.',
                ]),
            Field::make('PD_2'),
            Field::make('PD_3')->radio([
                'at the same pace (i.e., 1 week between topics to allow for practice and facilitate deeper learning)',
                'faster than the current online psychoeducation program (e.g., no time limit between topics)',
                'slower than the current online psychoeducation program'
            ])
        ];
    }
}
