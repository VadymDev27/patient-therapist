<?php

namespace App\Surveys\Therapist\Prep;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Trait\Prep;
use Illuminate\Support\Facades\Auth;
use Surveys\AbstractSurvey;
use Surveys\Exception\WeekOutOfBoundsException;

class Prep1 extends AbstractSurvey
{
    use Prep;

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Preparatory Video 1';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'prep-1';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        DisplayVideo::class,
        VideoFeedback::class
    ];

    protected function number(): int
    {
        return 1;
    }

    protected function redirectTo(): string
    {
        return route('thank-you', ['slug' => 'prep']);
    }
}
