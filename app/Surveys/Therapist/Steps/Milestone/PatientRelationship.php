<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class PatientRelationship extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.patient-relationship';

    protected static function fields(): array
    {
        return [
            Field::make('CWR_1')
                ->conditionalFields(['CWR_2', 'CWR_3', 'CWR_3_Other'],
                    'No'),
            Field::make('CWR_2'),
            Field::make('CWR_3')
                ->multiSelect([
                    'the client or I relocated too far away to continue treatment;',
                    'the client no longer was able to afford treatment;',
                    'one of us became so ill that we had to discontinue treatment;',
                    'I closed my practice;',
                    'I determined that I was no longer the best person to treat this patient so referred them to another therapist; ',
                    'psychological decompensation and ended treatment prematurely;',
                    'received pressure from a friend or family member(s) to end treatment; ',
                    'deciding they did not want to engage in trauma treatment; ',
                    'not believing they had a DD so no longer wanted to be in DD treatment; ',
                    'rupture in alliance;',
                    'becoming upset with all forms of psychological treatment and ended all forms of treatment (e.g., individual therapy; psychiatric medications); ',
                    'other',
                    'The patient successfully resolved the problems they were dealing with, and left treatment with both of us agreeing that termination was appropriate.'
                ])->conditionalFields(['CWR_3_Other'],['other']),
            Field::make('CWR_3_Other'),
        ];
    }
}
