<?php

namespace App\Surveys\Patient\Weekly;

use Surveys\AbstractFirstWeek;
use App\Surveys\Action\IncrementParticipantWeek;
use App\Surveys\FirstWeek as FirstWeekTemplate;
use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use App\Surveys\Patient\Steps\Weekly\InformationSheetFeedback;
use App\Surveys\Patient\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\VideoFeedback;
use App\Surveys\Patient\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Weekly\FirstWeek as TherapistFirstWeek;
use Surveys\AbstractSurvey;

class FirstWeek extends TherapistFirstWeek
{
    public static string $role = 'patient';

    protected static array $stepClasses = [
        SelfKindness::class,
        CopingSkills::class,
        DisplayVideo::class,
        VideoFeedback::class
    ];

    protected static function categorySteps(string $role = ''): array
    {
        return [
            CopingSkills::class,
            InformationSheetFeedback::class,
            PracticeExercisesFeedback::class,
            SelfKindness::class,
            VideoFeedback::class,
            WrittenExercisesFeedback::class
        ];
    }
}
