<?php

namespace App\Surveys\Therapist\Weekly;

use App\Surveys\Action\IncrementParticipantWeek;
use App\Surveys\Action\MakeDiscontinuationSurvey;
use Surveys\AbstractWeeklySurvey;
use App\Surveys\FirstWeek as FirstWeekTemplate;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Weekly\InterventionImpact;
use App\Surveys\Therapist\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Therapist\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Trait\UsesFinalWeek;
use App\Surveys\Trait\Weekly;
use App\Surveys\WeeklySurvey as WeeklySurveyTemplate;
use Illuminate\Support\Facades\Auth;
use Surveys\AbstractSurvey;
use Surveys\Exception\WeekOutOfBoundsException;
use Surveys\StepRenderer;

class WeeklySurvey extends FirstWeek
{
    use UsesFinalWeek;

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        WrittenExercisesFeedback::class,
        PracticeExercisesFeedback::class,
        InterventionImpact::class,
        DisplayVideo::class,
        VideoFeedback::class
    ];


    private int $minWeek = 2;
    private int $maxWeek;
    private ?int $week = null;

    public static string $slug = 'weekly';

    public function title(): string
    {
        return "Weekly Activities - Week {$this->getWeek()}";
    }

    public function getWeek(): int
    {
        if (is_null($this->week)) {
            $week = Auth::user()->week;
        if ($week < $this->minWeek || $week >= static::finalWeek()) {
            throw new WeekOutOfBoundsException();
        }
        $this->week = $week;
        }
        return $this->week;
    }

    protected string $onCompleteAction = IncrementParticipantWeek::class;

    protected static array $middleware = ['weekly-content'];
}
