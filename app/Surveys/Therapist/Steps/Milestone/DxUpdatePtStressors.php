<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class DxUpdatePtStressors extends SurveyStep
{
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part V';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.dx-update-pt-stressors';

    protected static function fields(): array
    {
        return array_merge(
            [ Field::make('DxUpdate_1')->radio([
                'Dissociative Identity Disorder (DSM-5 or ICD-10)','Other Specified Dissociative Disorder (DSM-5)','Dissociative Disorder, Unspecified (ICD-10)','Post-Traumatic Stress Disorder with Dissociative Symptoms (i.e., depersonalization and/or derealization; DSM-5 or ICD-10)','Complex Post Traumatic Stress Disorder (ICD-11)','None of the above; patient no longer meets criteria for any of the above disorders'
            ])],
            array_map(
                Field::closure(),
                appendStringToEach(range(1,18),'PtStressors_')
            )
        );
    }
}
