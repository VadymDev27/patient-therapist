<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class SelfHarmTherapyTx extends SurveyStep
{
    private static array $treatmentOptions = ['Psychiatric medications','Group therapy', 'Substance Disorder Groups', 'Family therapy', 'Couples therapy', 'Art therapy', 'Other forms of expressive therapy (movement, writing, etc.)','Other treatments'];
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part V';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.self-harm-therapy-tx';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return array_merge(
            appendStringToEach(range(1,4),'RecentSH_'),
            ['TherapyFocus_1', 'OtherTx_1', 'OtherTx_1_OtherText'],
        );
    }

    protected static function fields(): array
    {
        return array_merge(
            array_map(
                Field::closure(),
                appendStringToEach(range(1,4),'RecentSH_')
            ),
            [
                Field::make('TherapyFocus_1')->radio([
                    1 => 'Stabilization & establishing safety',
                    2 => '',
                    3 => 'Processing trauma\'s impact',
                    4 => '',
                    5 => 'Reconnection with self and others'
                ]),
                Field::make('OtherTx_1')
                    ->multiSelect(static::$treatmentOptions)
                    ->conditionalFields('OtherTx_Other', static::$treatmentOptions[7]),
                Field::make('OtherTx_Other')
            ]
        );
    }
}
