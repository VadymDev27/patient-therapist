<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Illuminate\Support\Arr;
use Surveys\Field;
use Surveys\SurveyStep;


class PITQ extends SurveyStep
{
    private static array $questions = [
        'I have been diagnosed with dissociative symptoms and agree with this diagnosis.',
        'I collaborate well with my therapist, and when there are problems between us, I talk to my therapist about them so that we can resolve them together.',
        'I am compassionate and fair with myself; that is, I respond to myself with as much empathy as I would show someone else in the same situation.',
        'I’m aware of the thoughts, feelings, and body sensations that indicate I’m getting anxious or overwhelmed.',
        'I use relaxation techniques (such as deep breathing, peaceful imagery, music, tensing and relaxing my muscles) to safely help myself relax and feel better when I start to get “stressed” or anxious. ',
        'I use grounding techniques when I start to feel too much (intense feelings or impulses), too little (e.g., getting numb), begin to slip into the past, or notice that I’m at risk of dissociating. (Grounding examples: orient to the present; focus on my surroundings; pay attention to my five senses.)',
        'If things start to remind me of the past, I work to separate the past from the present by looking for and reminding myself of the differences between now and then.',
        'I manage intrusive memories and flashbacks using containment strategies (imagery techniques used to contain and manage PTSD symptoms). ',
        'I am aware of my emotions and body sensations.',
        'I am able to feel my emotions without getting overwhelmed.',
        'I am able to think about and control my impulses. (Example: I can notice and not act on impulses to do things that are not in my best long-term interests.)  ',
        'I reach out to treatment providers if I have difficulty controlling severe unhealthy impulses despite using recovery-focused coping skills (e.g., grounding, past vs. present, containment).',
        'I know that the traumas I have experienced were not my fault.',
        'I manage everyday life well. (Examples: I regularly eat, bathe, pay bills on time, etc.).',
        'I am able to account for all that I do; that is, I don’t “lose time” or find evidence of having done something I do not remember.',
        'I am able to deal with stressful situations without dissociating. ',
        'I am able to maintain healthy personal and professional relationships.',
        'I value my physical well-being, and do not do things that hurt my body. (Examples: I don’t cut or burn my body or attempt suicide.)',
        'I value my health and do not do things that put me at risk. (Examples: I do not abuse drugs, throw up after eating, drive unsafely, have unsafe sex, etc.) ',
        'Life feels meaningful and rewarding.',
        'I have a generally positive view of myself. ',
        'I have a generally positive view of other people.  ',
        'My sense of who I am includes many important things beyond having experienced trauma.',
        'I am able to experience intimacy without intense fear, shame, flashbacks, or dissociation, and with some pleasure. ',
        'I can explore the impacts of traumas I have experienced.',
        'I am able to grieve losses related to trauma.',
    ];

    private static array $options=[
        '0%' => 'never true',
        10 => '',
        20 => '',
        30 => '',
        40 => '',
        50 => '',
        60 => '',
        70 => '',
        80 => '',
        90 => '',
        '100%' => 'always true'
    ];

    private static array $conditionalQuestions = [
        'All parts of myself know that we are part of the same person and that we share one body. ',
        'All parts of myself are oriented to the present (know what day, month, and year it is).',
        'I pay attention to and am curious about what different parts of myself are feeling.',
        'I’m aware of which parts of myself are contributing to my actions.',
        'All parts of myself know and can independently use recovery-focused coping skills (e.g., grounding, past vs. present, containment).',
        'All parts of myself communicate and cooperate well.',
    ];
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Progress In Treatment Questionnaire';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.pitq';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return appendStringToEach(Arr::prepend(range(1, 32), '27a'), 'PITQ_');
    }

    protected static function fields(): array
    {
        $callback = fn (string $name, string $question) =>
            Field::make($name)->question($question)->radio(static::$options);
        return array_merge(
            array_map(
                $callback,
                appendStringToEach(range(1, 26), 'PITQ_'),
                static::$questions
            ),
            [
                Field::make('PITQ_27a')
                    ->conditionalFields(
                        $fields = appendStringToEach(range(27, 32), 'PITQ_'),
                        'Yes'
                    )->question('Do you have dissociated parts or voices?')
            ],
            array_map($callback,$fields,static::$conditionalQuestions),
        );
    }
}
