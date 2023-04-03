<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class SCS extends SurveyStep
{
    private static array $questions = [
        'When I fail at something important to me, I become consumed by feelings of inadequacy.',
        'I try to be understanding and patient towards those aspects of my personality I don’t like. ',
        'When something painful happens, I try to take a balanced view of the situation. ',
        'When I’m feeling down, I tend to feel like most other people are probably happier than I am.',
        'I try to see my failings as part of the human condition.',
        'When I’m going through a very hard time, I give myself the caring and tenderness I need. ',
        'When something upsets me I try to keep my emotions in balance. ',
        'When I fail at something that’s important to me, I tend to feel alone in my failure',
        'When I’m feeling down I tend to obsess and fixate on everything that’s wrong.',
        'When I feel inadequate in some way, I try to remind myself that feelings of inadequacy are shared by most people.',
        'I’m disapproving and judgmental about my own flaws and inadequacies. ',
        'I’m intolerant and impatient towards those aspects of my personality I don’t like. ',
    ];
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Self-Compassion Scale';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.scs';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return appendStringToEach(range(1,12), 'SCS_');
    }

    protected static function fields(): array
    {
        $options = [
            1 => 'Almost never',
            2 => '',
            3 => '',
            4 => '',
            5 => 'Almost always'
        ];

        return array_map(
            fn (string $name, string $question) =>
                Field::make($name)
                    ->question($question)
                    ->radio($options)->required(),
            appendStringToEach(range(1,12), 'SCS_'),
            static::$questions
        );
    }
}
