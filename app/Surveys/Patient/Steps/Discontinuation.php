<?php

namespace App\Surveys\Patient\Steps;

use Surveys\Field;
use Surveys\SurveyStep;


class Discontinuation extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Discontinuation Survey';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.discontinuation.page-one';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return ['PDS', 'PDS_Other_Text'];
    }

    protected static function fields(): array
    {
        return [
            Field::make('PDS')
                ->multiSelect([
                    'I have improved to the point where I no longer need treatment.',
                    'I would like to continue participating in the study, but my therapist is unable to continue participating.',
                    'I would like to continue participating in the study, but my therapist is unwilling to continue participating.  ',
                    'I believe that participating in this study is not helping me.',
                    'My therapist believes that participating in this study is not helping me.',
                    'I am not ready to do the work required to be successful in this study.',
                    'The study takes too much time for me to continue participating.',
                    'The study takes too much time for my therapist to continue participating.',
                    'My therapist or I relocated too far away to continue treatment; ',
                    'I can no longer afford treatment; ',
                    'One of us became so ill that we had to discontinue treatment; ',
                    'My therapist closed their practice or had to substantially reduce the number of hours they are working; ',
                    'My therapist and I were no longer able to work productively together so I will work with another therapist; ',
                    'I became upset with the members of my treatment team and ended treatment (e.g., individual therapy; psychiatric medications; etc.); ',
                    'I was getting worse rather than better in therapy so quit treatment; ',
                    'Although I do not think I was getting worse, I do not think treatment was helping me so I quit;',
                    'I was under pressure from a friend or family member(s) to end treatment; ',
                    'I decided that I do not want to engage in treatment that addresses dissociation so I quit treatment; ',
                    'I do not believe I suffer from dissociation so I no longer wanted to be in treatment that addresses dissociation; ',
                    'I decided that I do not want to continue being in trauma treatment;',
                    'Other'
                ])->conditionalFields('PDS_Other','Other'),
            Field::make('PDS_Other')
        ];
    }
}
