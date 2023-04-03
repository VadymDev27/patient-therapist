<?php

namespace App\Surveys\Patient\Prep;

use App\Surveys\Action\IncrementParticipantWeek;
use App\Surveys\Action\RandomizePair;
use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\VideoFeedback as WeeklyVideoFeedback;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Prep\Prep3 as TherapistPrep3;
use App\Surveys\Trait\Prep;
use Illuminate\Support\Facades\Auth;
use Surveys\AbstractSurvey;
use Surveys\Exception\WeekOutOfBoundsException;

class Prep3 extends TherapistPrep3
{
    public static string $role = 'patient';

    protected static array $stepClasses = [
        DisplayVideo::class,
        WeeklyVideoFeedback::class
    ];

    protected string $onCompleteAction = IncrementParticipantWeek::class;
}
