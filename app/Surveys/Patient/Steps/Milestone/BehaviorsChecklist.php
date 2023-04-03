<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class BehaviorsChecklist extends SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Difficulties in Emotional Regulation Scale';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.behaviors-checklist';

    protected static function fields(): array
    {
        $stem = "On how many of the <b>PAST 30 DAYS</b> ";

        $q_pt1 = [
            'did you purposefully hurt yourself (for example, cut yourself)?',
            'did you attempt suicide?',
            'have you done something that in retrospect was dangerous enough to kill you?',
            'did you drink alcohol?'
        ];

        $q_pt2 = [
            'did you do something very impulsive (spending sprees, lost your temper and really shouted at someone, threatened to harm (or actually harmed) someone else, driven far too fast, done anything against the law, etc.)?',
            'did you stay in bed for more than 10 out of 24 hours?',
            'did you have pain in your body?',
            'did you work for pay?',
            'did you work without pay (e.g., volunteering) or attend school?',
            'have you used grounding, containment, relaxation, imagery or other techniques to manage your symptoms?',
            'did you feel some good feelings even if it was for a brief period (e.g.  happiness,contentment, joy)?',
            'have you participated in social activities such as visiting friends, attending clubs or meetings, or going to parties (not including therapy activities)?'
        ];

        $callback = fn (int $num, string $question) => Field::make('BC_' . $num)->question($stem . $question);
        return array_merge(
            array_map($callback, range(1, 4), $q_pt1),
            [
                Field::make('BC_4b')->optional(),
                Field::make('BC_5')->question(
                    $stem . 'did you get high on street or prescription drugs?'
                ),
                Field::make('BC_5b')->optional()
            ],
            array_map($callback, range(6, 13), $q_pt2),
            [
                Field::make('BC_14')->radio($options = [
                    1 => 'A lot',
                    2 => 'Some',
                    3 => 'A little',
                    4 => 'Not at all'
                ])->question("In the <b>PAST 30 DAYS</b>, how much did your <b>psychological</b> problems interfere with your life or activities? (choose one)"),
                Field::make('BC_15')
                    ->radio($options)
                    ->question("In the <b>PAST 30 DAYS</b>, how much did your <b>medical</b> problems interfere with your life or activities? (choose one)")
            ]
        );
    }
}
