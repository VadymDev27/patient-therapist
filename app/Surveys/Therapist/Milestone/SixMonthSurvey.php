<?php

namespace App\Surveys\Therapist\Milestone;

use App\Surveys\Action\MakeDiscontinuationSurvey;
use App\Surveys\Action\RandomizePair;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use Surveys\AbstractSurvey;

class SixMonthSurvey extends AbstractSurvey
{
    use \App\Surveys\Trait\Milestone;

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Milestone Survey - 6 Months';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = '6-month';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        PatientRelationship::class,
        DxUpdatePtStressors::class,
        SelfHarmTherapyTx::class,
        TherapeuticActivities::class
    ];

    protected static array $middleware = ['milestone:6-month','consent'];
}
