<?php

namespace App\Surveys\Therapist\Steps;

use Surveys\Field;
use Surveys\SurveyStep;


class Discontinuation extends SurveyStep
{
    private static array $options = [
        'The patient has improved to the point where they no longer need treatment.',
        'I would like to continue participating in the study, but my patient is unable to continue participating.  ',
        'I would like to continue participating in the study, but my patient is unwilling to continue participating.  ',
        'The patient believes that participating in this study is not helping.',
        'I believe that participating in this study is not helping my patient.',
        'I believe my patient is not ready to do the work required to be successful in the study.',
        'The study takes too much time for my patient to continue participating.',
        'The study takes too much time for me to continue participating.',
        'The patient or I relocated too far away to continue treatment; ',
        'The patient no longer was able to afford treatment; ',
        'One of us became so ill that we had to discontinue treatment; ',
        'I closed my practice or had to substantially reduce the number of hours I am working; ',
        'I determined I was no longer the best person to treat this patient so referred them to another therapist; ',
        'The patient became embroiled in negative feelings towards me; ',
        'The patient became upset with all forms of psychological treatment and ended all forms of treatment (e.g., individual therapy; psychiatric medications); ',
        'The patient psychologically decompensated and ended treatment prematurely; ',
        'The patient received pressure from a friend or family member(s) to end treatment; ',
        'The patient decided they did not want to engage in treatment that addressed dissociation; ',
        'The patient did not believe they had a DD so no longer wanted to be in treatment that addressed dissociation; ',
        'The patient decided they did not want to continue in trauma treatment;',
        'The patient felt that treatment was not helping;',
        'The patient had a strongly negative reaction to some of the content in the program.',
        'Other'
    ];
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Discontinuation Survey';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.discontinuation.page-one';

    protected static function fields(): array
    {
        return [
            Field::make('TDS')
                ->multiSelect(static::$options)
                ->conditionalFields('TDS_22_specify',static::$options[21])
                ->conditionalFields('TDS_Other','Other'),
            Field::make('TDS_22_specify'),
            Field::make('TDS_Other')
        ];
    }
}
