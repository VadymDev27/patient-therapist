<?php

namespace App\Surveys\Therapist\Steps\Screening;

use Surveys\Field;
use Surveys\SurveyStep;
use Surveys\Trait\UsesFields;

class PageOne extends SurveyStep
{
    private static array $options = [
        'Specialized training in trauma as a student/resident/intern/postdoc',
        'Specialized training in dissociative disorders (DD) as a student/resident/intern/postdoc',
        'Supervision/Consultation with trauma disorders/DD specialist(s)',
        'Work(ed) within a trauma-focused unit, center, or practice',
        'Graduated from ISSTD (or similar professional organization) training program for treating Dissociative Disorders (check all that apply)',
        'Attended workshops or lectures on treating dissociative disorders',
        'Learned by reading one or more books specifically about dissociative disorder patients',
        'Learned about dissociative disorders only by treating dissociative disordered patients',
        'Other/s'
    ];
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part I';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.screening.page-one';

    protected static function fields(): array
    {
        return array_merge(
            [
                Field::make('TSS_Demographics_1')
                    ->withHiddenOther()
                    ->radio(['Psychiatrist', 'Psychologist', 'Family Therapist', 'Social Worker', 'Nurse', 'Expressive Therapist', 'Other']),
                Field::make('TSS_Demographics_2')
                    ->withHiddenOther()
                    ->multiSelect(['Woman', 'Man', 'Nonbinary', 'Trans', 'Other']),
                Field::make('TSS_Demographics_3'),
                Field::make('TSS_Demographics_4')
                    ->multiSelect(['Caucasian', 'Asian', 'Latino / Hispanic', 'African', 'Native / Aboriginal / Indigenous / First People', 'Other'])
                    ->withHiddenOther(),
                Field::make('TSS_Demographics_5'),
                Field::make('TSS_Demographics_6')->radio(['Biological','Psychodynamic','Humanistic / experiential','Cognitive-Behavioral','Family Systems','Biological','Other'])->withHiddenOther(),
                Field::make('TSS_Demographics_7')
                    ->multiSelect([
                    'Private practice','School','Forensic','Clinic / hospital outpatient','Hospital inpatient / partial program', 'Residential facility', 'Other'
                ])
                    ->withHiddenOther(),
            ],
                array_map(Field::closure(), appendStringToEach(range(8,11), 'TSS_Demographics_')),
            [
                Field::make('TSS_Demographics_12')->radio($scale = [
                    '1' => 'Not at all',
                    '2' => '',
                    '3' => 'Fairly',
                    '4' => '',
                    '5' => 'Completely'
                ]),
                Field::make('TSS_Demographics_13')->radio($scale),
                Field::make('TSS_Demographics_14')
                    ->multiSelect(static::$options)
                    ->conditionalFields(['TSS_Demographics_14_3_specify'], static::$options[2])
                    ->conditionalFields(['TSS_Demographics_14_5_specify'], static::$options[4])
                    ->conditionalFields(['TSS_Demographics_14_9_specify'], static::$options[8]),
                Field::make('TSS_Demographics_14_3_specify'),
                Field::make('TSS_Demographics_14_5_specify')->multiSelect([
                    'Standard or beginning level training',
                    'Advanced',
                    'Master',
                    'Online course',
                    'Other course'
                ]),
                Field::make('TSS_Demographics_14_9_specify')
            ]
        );
    }
}
