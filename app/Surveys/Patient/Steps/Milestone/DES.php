<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class DES extends SurveyStep
{
    private static array $questions=[
        'Some people have the experience of driving or riding in a car or bus or subway and suddenly realizing that they don’t remember what has happened during all or part of the trip.  Select a number to show what percentage of the time this happens to you.',
        'Some people find that sometimes they are listening to someone talk and they suddenly realize that they did not hear part or all of what was said. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of finding themselves in a place and having no idea how they got there. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of finding themselves dressed in clothes that they don’t remember putting on. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of finding new things among their belongings that they do not remember buying. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find that they are approached by people that they do not know who call them by another name or insist that they have met them before. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes have the experience of feeling as though they are standing next to themselves or watching themselves do something and they actually see themselves as if they were looking at another person. Select a number to show what percentage of the time this happens to you.',
        'Some people are told that they sometimes do not recognize friends or family members. Select a number to show what percentage of the time this happens to you.',
        'Some people find that they have no memory for some important events in their lives (for example, a wedding or graduation). Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of being accused of lying when they do not think that they have lied. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of looking in a mirror and not recognizing themselves. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of feeling that other people, objects, and the world around them are not real. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of feeling that their body does not seem to belong to them. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of sometimes remembering a past event so vividly that they feel as if they were reliving that event. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of not being sure whether things that they remember happening really did happen or whether they just dreamed them. Select a number to show what percentage of the time this happens to you.',
        'Some people have the experience of being in a familiar place but finding it strange and unfamiliar. Select a number to show what percentage of the time this happens to you.',
        'Some people find that when they are watching television or a movie they become so absorbed in the story that they are unaware of other events happening around them. Select a number to show what percentage of the time this happens to you.',
        'Some people find that they become so involved in a fantasy or daydream that it feels as though it were really happening to them. Select a number to show what percentage of the time this happens to you.',
        'Some people find that they are sometimes able to ignore pain. Select a number to show what percentage of the time this happens to you.',
        'Some people find that they sometimes sit staring off into space, thinking of nothing, and are not aware of the passage of time. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find that when they are alone they talk out loud to themselves. Select a number to show what percentage of the time this happens to you.',
        'Some people find that in one situation they may act so differently compared with another situation that they feel almost as if they were two different people. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find that in certain situations they are able to do things with amazing ease and spontaneity that would usually be difficult for them (for example, sports, work, social situations, etc.). Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find that they cannot remember whether they have done something or have just thought about doing it (for example, not knowing whether they have just mailed a letter or have just thought about mailing it). Select a number to show what percentage of the time this happens to you.',
        'Some people find evidence that they have done things that they do not remember doing. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find writings, drawings, or notes among their belongings that they must have done but cannot remember doing. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes find that they hear voices inside their head that tell them to do things or comment on things that they are doing. Select a number to show what percentage of the time this happens to you.',
        'Some people sometimes feel as if they are looking at the world through a fog so that people and objects appear far away or unclear. Select a number to show what percentage of the time this happens to you.',
    ];
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Dissociative Experiences Scale';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.des';

    protected static function fields(): array
    {
        return array_map(
            fn (string $name, string $question) => Field::make($name)->radio(range(0,100,10))->question($question),
            appendStringToEach(range(1,28), 'DES_'),
            static::$questions
        );
    }

    public static function generateFakeData(int $score=30): array
    {
        return $score
                ? collect(static::fieldNames())
                    ->flip()
                    ->map(fn ($item) => $score)
                    ->toArray()
                : parent::generateFakeData();
    }
}
