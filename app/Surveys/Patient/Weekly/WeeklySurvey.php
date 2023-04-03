<?php

namespace App\Surveys\Patient\Weekly;

use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use Surveys\AbstractWeeklySurvey;
use App\Surveys\Patient\Steps\Weekly\InformationSheetFeedback;
use App\Surveys\Patient\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\VideoFeedback;
use App\Surveys\Patient\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Steps\DisplayVideo;
use App\Surveys\Therapist\Weekly\WeeklySurvey as TherapistWeeklySurvey;

class WeeklySurvey extends TherapistWeeklySurvey
{
    public static string $role = 'patient';

    protected static array $stepClasses = [
        InformationSheetFeedback::class,
        WrittenExercisesFeedback::class,
        PracticeExercisesFeedback::class,
        SelfKindness::class,
        CopingSkills::class,
        DisplayVideo::class,
        VideoFeedback::class
    ];

    protected static function categorySteps(string $role = ''): array
    {
        return [
            InformationSheetFeedback::class,
            WrittenExercisesFeedback::class,
            PracticeExercisesFeedback::class,
            SelfKindness::class,
            CopingSkills::class,
            VideoFeedback::class
        ];
    }
}
