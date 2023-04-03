<?php

namespace App\Surveys\Trait;

use App\Surveys\Patient\Steps\Weekly\CopingSkills;
use App\Surveys\Patient\Steps\Weekly\InformationSheetFeedback;
use App\Surveys\Patient\Steps\Weekly\PracticeExercisesFeedback;
use App\Surveys\Patient\Steps\Weekly\SelfKindness;
use App\Surveys\Patient\Steps\Weekly\VideoFeedback;
use App\Surveys\Patient\Steps\Weekly\WrittenExercisesFeedback;
use App\Surveys\Therapist\Steps\Weekly\InterventionImpact;
use App\Surveys\Therapist\Steps\Weekly\PracticeExercisesFeedback as WeeklyPracticeExercisesFeedback;
use App\Surveys\Therapist\Steps\Weekly\VideoFeedback as WeeklyVideoFeedback;
use App\Surveys\Therapist\Steps\Weekly\WrittenExercisesFeedback as WeeklyWrittenExercisesFeedback;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Surveys\Exception\WeekOutOfBoundsException;

trait Prep
{
    protected static function category(): string
    {
        return 'prep';
    }

    protected static function categorySteps(string $role=''): array
    {
        switch ($role) {
            case 'patient':
                return [
                    CopingSkills::class,
                    SelfKindness::class,
                    VideoFeedback::class,
                ];
            case 'therapist':
                return [
                    WeeklyVideoFeedback::class,
                ];
            default:
                return [];
        }
    }

    public function isPrep(): bool
    {
        return true;
    }

    protected function number(): int
    {
        return 0;
    }

    public function getWeek(): ?int
    {
        if ($this->getUser()->getPrepVideoNumber() === $this->number()) {
            return $this->number();
        }
        throw new WeekOutOfBoundsException();
    }
}
