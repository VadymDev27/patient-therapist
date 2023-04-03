<?php

namespace App\Surveys\Therapist\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;
use Surveys\Trait\UsesFields;

class PageTwo extends SurveyStep
{
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part II';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.screening.page-two';

    protected static function fields(): array
    {
        return array_merge(
            [Field::make('TSS_Diagnosis_1')->required()->radio([
                'Dissociative Identity Disorder (DSM-5 or ICD-10)',
                'Other Specified Dissociative Disorder (DSM-5)',
                'Dissociative Disorder, Unspecified (ICD-10)',
                'Post-Traumatic Stress Disorder with Dissociative Symptoms (i.e., depersonalization and/or derealization; DSM-5 or ICD-10)',
                'Complex PTSD (ICD-11)'
            ])],
            array_map(
                fn (string $name) => Field::make($name)->required(),
                array_merge(
                    appendStringToEach(range(1,7),'TSS_PTSD_'),
                    appendStringToEach(range(1,3),'TSS_CPTSD_'),
                    appendStringToEach(range(1,4),'TSS_OSDD_'),
                    appendStringToEach(range(1,5),'TSS_DID_'),
                )
            )
                );
    }
}
