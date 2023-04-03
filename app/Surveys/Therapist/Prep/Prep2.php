<?php

namespace App\Surveys\Therapist\Prep;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Trait\Prep;
use Surveys\AbstractSurvey;

class Prep2 extends Prep1
{
     /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Preparatory Video 2';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'prep-2';

    protected function number(): int
    {
        return 2;
    }
}
