<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class PCLC extends SurveyStep
{
    private static array $questions=[
        'Repeated, disturbing, and unwanted memories of the stressful experience?',
        'Repeated, disturbing dreams of the stressful experience?',
        'Suddenly feeling or acting as if the stressful experience were actually happening again <i>(as if you were actually back there reliving it)</i>?',
        'Feeling very upset when something reminded you of the stressful experience?',
        'Having strong physical reactions when something reminded you of the stressful experience <i>(for example, heart pounding, trouble breathing, sweating)</i>?',
        'Avoiding memories, thoughts, or feelings related to the stressful experience?',
        'Avoiding external reminders of the stressful experience <i>(for example, people, places, conversations, activities, objects, or situations)</i>?',
        'Trouble remembering important parts of the stressful experience?',
        'Having strong negative beliefs about yourself, other people, or the world <i>(for example, having thoughts such as: I am bad, there is something seriously wrong with me, no one can be trusted, the world is completely dangerous)</i>?',
        'Blaming yourself or someone else for the stressful experience or what happened after it?',
        'Having strong negative feelings such as fear, horror, anger, guilt, or shame?',
        'Loss of interest in activities that you used to enjoy?',
        'Feeling distant or cut off from other people?',
        'Trouble experiencing positive feelings <i>(for example, being unable to feel happiness or have loving feelings for people close to you)</i>?',
        'Irritable behavior, angry outbursts, or acting aggressively?',
        'Taking too many risks or doing things that could cause you harm?',
        'Being “super alert” or watchful or on guard?',
        'Feeling jumpy or easily startled?',
        'Having difficulty concentrating?',
        'Trouble falling or staying asleep?'
    ];
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'PTSD Checklist - Civilian Version';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.pclc';

    protected static function fields(): array
    {
        return array_map(
            fn (string $name, string $question) =>
                Field::make($name)
                    ->question($question)
                    ->radio(range(0,4)),
            appendStringToEach(range(1,20), 'PCL_'),
            static::$questions
        );
    }
}
