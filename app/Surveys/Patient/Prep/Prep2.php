<?php

namespace App\Surveys\Patient\Prep;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\VideoFeedback as WeeklyVideoFeedback;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Prep\Prep2 as TherapistPrep2;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback;
use App\Surveys\Trait\Prep;
use Illuminate\Support\Facades\Auth;
use Surveys\AbstractSurvey;
use Surveys\Exception\WeekOutOfBoundsException;

class Prep2 extends TherapistPrep2
{
    public static string $role = 'patient';

    protected static array $stepClasses = [
        DisplayVideo::class,
        WeeklyVideoFeedback::class
    ];
}
