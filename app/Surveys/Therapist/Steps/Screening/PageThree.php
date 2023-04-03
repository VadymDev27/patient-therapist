<?php

namespace App\Surveys\Therapist\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;


class PageThree extends SurveyStep
{
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part III';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.screening.page-three';

    protected static function fields(): array
    {
        return [
            Field::make('TSS_Cooccurring_1')
                ->multiSelect([
                    'Depressive Episode or Depressive Disorder',
                    'Manic Episode or Bipolar / Related Disorder',
                    'Obsessive-Compulsive Related Disorder',
                    'Somatic Symptom / Somatoform Related Disorder',
                    'Substance-Related Disorder',
                    'Schizophrenia Spectrum / Other Psychotic Disorder',
                    'Posttraumatic Stress Disorder (PTSD)',
                    'Complex Post Traumatic Stress Disorder
                    (CPTSD; ICD-11, 6B41)',
                    'Other Trauma- / Stressor-Related Disorder',
                    'Enduring personality change after catastrophic experience (ICD F62.0)',
                    'Anxiety Disorder'
                ])->optional(),
            Field::make('TSS_Cooccurring_2')
                ->multiSelect([
                    'Cluster A Personality Disorder',
                    'Cluster B Personality Disorder',
                    'Cluster C Personality Disorder',
                    'Other Specified Personality Disorder or Unspecified Personality Disorder'
                ])->optional(),
            Field::make('TSS_PtFunctioning_1')->radio(
                [
                    '1' => 'very poor / unstable / absent',
                    '2' => '',
                    '3' => '',
                    '4' => '',
                    '5' => 'stable / supportive / close'
                ]
            ),
            Field::make('TSS_PtFunctioning_2')->radio([
                '1' => 'unable to keep a job',
                '2' => '',
                '3' => '',
                '4' => '',
                '5' => 'working to full potential'
            ])
        ];
    }


}
