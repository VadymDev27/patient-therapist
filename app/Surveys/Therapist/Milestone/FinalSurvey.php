<?php

namespace App\Surveys\Therapist\Milestone;

use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use App\Surveys\Therapist\Steps\Milestone\ProgramDevelopment;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\StudyFeedback;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use Surveys\AbstractSurvey;

class FinalSurvey extends AbstractSurvey
{
    use \App\Surveys\Trait\Milestone;

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Milestone Survey - Final';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'final';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        PatientRelationship::class,
        DxUpdatePtStressors::class,
        SelfHarmTherapyTx::class,
        TherapeuticActivities::class,
        StudyFeedback::class,
        ProgramDevelopment::class
    ];

    protected static array $middleware = ['milestone:final','consent'];
}
