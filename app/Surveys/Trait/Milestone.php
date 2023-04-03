<?php

namespace App\Surveys\Trait;

use App\Surveys\Patient\Steps\Milestone\BehaviorsChecklist;
use App\Surveys\Patient\Steps\Milestone\DERS;
use App\Surveys\Patient\Steps\Milestone\DES;
use App\Surveys\Patient\Steps\Milestone\PCLC;
use App\Surveys\Patient\Steps\Milestone\PITQ;
use App\Surveys\Patient\Steps\Milestone\ProgramDevelopment;
use App\Surveys\Patient\Steps\Milestone\SCS;
use App\Surveys\Patient\Steps\Milestone\StudyFeedback;
use App\Surveys\Patient\Steps\Milestone\WHOQOL;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use App\Surveys\Therapist\Steps\Milestone\ProgramDevelopment as MilestoneProgramDevelopment;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\StudyFeedback as MilestoneStudyFeedback;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;

trait Milestone
{
    protected static function category(): string
    {
        return 'milestone';
    }


    protected static function categorySteps(string $role=''): array
    {
        switch ($role) {
            case 'patient':
                return [
                    BehaviorsChecklist::class,
                    DERS::class,
                    DES::class,
                    PCLC::class,
                    PITQ::class,
                    ProgramDevelopment::class,
                    SCS::class,
                    StudyFeedback::class,
                    WHOQOL::class
                ];
            case 'therapist':
                return [
                    DxUpdatePtStressors::class,
                    PatientRelationship::class,
                    MilestoneProgramDevelopment::class,
                    SelfHarmTherapyTx::class,
                    MilestoneStudyFeedback::class,
                    TherapeuticActivities::class
                ];
            default:
                return [];
        }
    }
}
