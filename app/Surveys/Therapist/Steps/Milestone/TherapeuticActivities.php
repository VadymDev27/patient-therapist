<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class TherapeuticActivities extends SurveyStep
{
    private static array $questions=[
        'Diagnosis: formally assessing the presence and severity of idssociative and other psychiatric disorders',
        'Educating about disorders and treatment: educating about dissociation, other trauma-related symptoms and difficulties, comorbid disorders, treatment.',
        'Acceptance of Dissociative Disorder (DD):  Working on feelings about having a trauma-related DD.',
        'Working to establish, maintain, and repair the therapeutic alliance.',
        'Teaching and utilizing grounding techniques (e.g., orienting and anchoring to the present) to manage feeling too much (intense emotions or urges) or too little (dissociative symptoms).',
        'Teaching and utilizing containment imagery techniques to control the intrusiveness of traumatic material.',
        'Teaching and utilizing “ego-strengthening” and emotion regulation skills (e.g., healthy self-compassion, encouraging self-talk, deep breathing, calming imagery, relaxation training) to promote better overall functioning.',
        'Teaching and utilizing cognitive/cognitive behavioral techniques to resolve problematic thought patterns (e.g., confusing past and present; self-blame for abuse; delusions of separateness among dissociative self-states, etc.).',
        'Assessing response to medications and adjusting them, if applicable.',
        'Assessing and addressing safety, including: discussing the frequency, antecedents to, and functions of, self-harm, risky and/or unsafe behaviors, suicidal behaviors, and aggressive behaviors toward others; developing safety management plans.',
        'Developing and practicing affect tolerance and impulse control.',
        'Working on improving awareness of emotions.',
        'Working on improving awareness of body sensations.',
        'Identifying dissociative self-states/alters, their roles, and working with them either directly or indirectly (e.g., “talking through”).',
        'Working to establish internal communication and cooperation among dissociative self-states/alters.',
        'Assessing current relationships for dysfunction, and if necessary, working to help client understand unhealthy relationship dynamics and ways to protect themselves in relationships.',
        'Working on improving daily functioning (e.g., parenting skills, hygiene, problems with their job, case management).',
        'Processing trauma by discussing specific events in vivid detail (e.g., abreactions, exposure).',
        'Processing delayed recall of trauma events.',
        'Processing trauma events using Eye Movement Desensitization and Reprocessing (EMDR).',
        'Processing patient’s acting out of affect and impulses that arose in response to therapy.',
        'Stabilizing patient following intrusions or boundary violations by alleged perpetrators.',
        'Working to stabilize patient related to stressful life situations (not including intrusions from alleged perpetrators;  e.g., loss of job or relationships, health problems).',
        'Processing when and why dissociation occurs.',
        'Discussing events within the therapeutic relationship as a means to help the client understand past and current relationships and behaviors.',
        'Teaching patient about attachment as a means for client to understand past and current relationships and behaviors.',
        'Stabilizing and improving affect regulation by using neurofeedback.',
        'Processing trauma using a “bottom-up” body or sensory approach.'
    ];
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'Part V';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.therapeutic-activities';

    protected static function fields(): array
    {
        $options = ['Never/NA', '', 'Sometimes', '', 'Very often'];
        return array_merge(
            array_map(
                fn (int $num) => Field::make('TherapeuticActivities_'.$num)->radio($options)->question(static::$questions[$num-1]),
                range(1,27)
            ),
            [
                Field::make('TherapeuticActivities_28')->radio($options)->question(static::$questions[27])
                    ->conditionalFields('TherapeuticActivities_29', [1,2,3,4]),
                Field::make('TherapeuticActivities_29')
            ]
        );
    }
}
