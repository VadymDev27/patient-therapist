<?php

namespace App\Surveys\Therapist\Milestone;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use Surveys\AbstractSurvey;

class InitialSurvey2 extends AbstractSurvey
{
    use \App\Surveys\Trait\Milestone;

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Therapist Initial Survey';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'initial-2';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        PatientRelationship::class,
        DxUpdatePtStressors::class,
        SelfHarmTherapyTx::class,
        TherapeuticActivities::class
    ];

    protected static array $middleware = ['milestone:initial-2','consent'];
}
