<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class DERS extends SurveyStep
{

    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'Difficulties in Emotional Regulation Scale';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.patient.milestone.ders';

    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fieldNameSuffixes(): array
    {
        return appendStringToEach(range(1,36), 'DERS_');
    }

    protected static function fields(): array
    {
        return array_map(
            fn (string $name, string $question) => Field::make($name)->question($question)->radio(range(1,5)),
            appendStringToEach(range(1,36), 'DERS_'),
            static::$questions
        );
    }

    private static array $questions = [
        'I am clear about my feelings.',
        'I pay attention to how I feel.',
        'I experience my emotions as overwhelming and out of control.',
        'I have no idea how I am feeling.',
        'I have difficulty making sense out of my feelings.',
        'I am attentive to my feelings.',
        'I know exactly how I am feeling.',
        'I care about what I am feeling.',
        'I am confused about how I feel.',
        'When I\'m upset, I acknowledge my emotions.',
        'When I\'m upset, I become angry with myself for feeling that way.',
        'When I\'m upset, I become embarrassed for feeling that way.',
        'When I\'m upset, I have difficulty getting work done.',
        'When I\'m upset, I become out of control.',
        'When I\'m upset, I believe that I will remain that way for a long time.',
        'When I\'m upset, I believe that I\'ll end up feeling very depressed.',
        'When I\'m upset, I believe that my feelings are valid and important.',
        'When I\'m upset, I have difficulty focusing on other things.',
        'When I\'m upset, I feel out of control.	',
        'When I\'m upset, I can still get things done.	',
        'When I\'m upset, I feel ashamed with myself for feeling that way.',
        'When I\'m upset, I know that I can find a way to eventually feel better.',
        'When I\'m upset, I feel like I am weak.',
        'When I\'m upset, I feel like I can remain in control of my behaviors.',
        'When I\'m upset, I feel guilty for feeling that way.',
        'When I\'m upset, I have difficulty concentrating.',
        'When I\'m upset, I have difficulty controlling my behaviors.',
        'When I\'m upset, I believe there is nothing I can do to make myself feel better.',
        'When I\'m upset, I become irritated with myself for feeling that way.',
        'When I\'m upset, I start to feel very bad about myself.',
        'When I\'m upset, I believe that wallowing in it is all I can do.',
        'When I\'m upset, I lose control over my behaviors.',
        'When I\'m upset, I have difficulty thinking about anything else.',
        'When I\'m upset, I take time to figure out what I\'m really feeling.',
        'When I\'m upset, it takes me a long time to feel better.',
        'When I\'m upset, my emotions feel overwhelming.'
    ];
}
