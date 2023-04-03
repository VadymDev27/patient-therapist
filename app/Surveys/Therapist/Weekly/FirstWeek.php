<?php

namespace App\Surveys\Therapist\Weekly;

use App\Surveys\Action\IncrementParticipantWeek;
use Surveys\AbstractFirstWeek;
use App\Surveys\FirstWeek as FirstWeekTemplate;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Weekly\InterventionImpact;
use App\Surveys\Therapist\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Therapist\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Trait\Weekly;
use Surveys\AbstractSurvey;

class FirstWeek extends AbstractSurvey
{
    public static string $role = 'therapist';

    protected static array $stepClasses = [
        DisplayVideo::class,
        VideoFeedback::class
    ];

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Weekly Activities - Week 1';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'first-week';

    protected function redirectTo(): string
    {
        return route('thank-you', ['slug' => 'weekly']);
    }

    protected string $onCompleteAction = IncrementParticipantWeek::class;

    protected static array $middleware = ['week:1', 'weekly-content'];

    public function getWeek(): int
    {
        return 1;
    }

    protected static function category(): string
    {
        return 'weekly';
    }

    protected static function categorySteps(string $role=''): array
    {
        return [
            InterventionImpact::class,
            PracticeExercisesFeedback::class,
            VideoFeedback::class,
            WrittenExercisesFeedback::class
        ];
    }
}
