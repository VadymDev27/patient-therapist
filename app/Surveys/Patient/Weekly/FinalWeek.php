<?php

namespace App\Surveys\Patient\Weekly;

use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use Surveys\AbstractFinalWeek;
use App\Surveys\Patient\Steps\Weekly\InformationSheetFeedback;
use App\Surveys\Patient\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Therapist\Weekly\FinalWeek as TherapistFinalWeek;

class FinalWeek extends TherapistFinalWeek
{
    public static string $role = 'patient';

    protected static array $stepClasses = [
        InformationSheetFeedback::class,
        WrittenExercisesFeedback::class,
        PracticeExercisesFeedback::class,
        SelfKindness::class,
        CopingSkills::class,
    ];

    protected static function categorySteps(string $role = ''): array
    {
        return [
            InformationSheetFeedback::class,
            WrittenExercisesFeedback::class,
            PracticeExercisesFeedback::class,
            SelfKindness::class,
            CopingSkills::class
        ];
    }
}
