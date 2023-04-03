<?php

namespace App\Surveys\Therapist\Prep;

use App\Surveys\Action\IncrementParticipantWeek;
use App\Surveys\Action\RandomizePair;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Trait\Prep;
use Surveys\AbstractSurvey;
use Surveys\Exception\WeekOutOfBoundsException;

class Prep3 extends Prep1
{
     /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Preparatory Video 3';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'prep-3';

    protected function number(): int
    {
        return 3;
    }

    protected string $onCompleteAction = IncrementParticipantWeek::class;

    protected function redirectTo(): string
    {
        return route('thank-you', ['slug' => 'prep-final']);
    }
}
