<?php

namespace App\Surveys\Therapist\Weekly;

use App\Surveys\Action\IncrementParticipantWeek;
use App\Surveys\Action\MakeDiscontinuationSurvey;
use Surveys\AbstractFinalWeek;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Weekly\InterventionImpact;
use App\Surveys\Therapist\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Therapist\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Trait\UsesFinalWeek;
use App\Surveys\Trait\Weekly;
use Surveys\AbstractSurvey;

class FinalWeek extends FirstWeek
{
    use UsesFinalWeek;

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        WrittenExercisesFeedback::class,
        PracticeExercisesFeedback::class,
        InterventionImpact::class
    ];

    public function title(): string
    {
        return 'Weekly Activities - Week ' . static::finalWeek();
    }

    protected function redirectTo(): string
    {
        return route('dashboard');
    }

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'final-week';

    /**
     * Prefix to all of the question field names.
     */
    public function getPrefix(): string
    {
        $week = static::$finalWeek;
        return "Week{$week}_";
    }

    protected string $onCompleteAction = IncrementParticipantWeek::class;

    public function getWeek(): int
    {
        return static::finalWeek();
    }

    public static function middleware(): array
    {
        return array_merge(parent::middleware(), ['weekly-content', 'week:' . static::finalWeek()]);
    }
}
