<?php

namespace App\Surveys\Therapist\Milestone;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Therapist\Steps\Milestone\DxUpdatePtStressors;
use App\Surveys\Therapist\Steps\Milestone\SelfHarmTherapyTx;
use App\Surveys\Therapist\Steps\Milestone\TherapeuticActivities;
use Surveys\AbstractSurvey;

class InitialSurvey extends AbstractSurvey
{
    use \App\Surveys\Trait\Milestone;
    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Therapist Initial Survey';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'initial';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        DxUpdatePtStressors::class,
        SelfHarmTherapyTx::class,
        TherapeuticActivities::class
    ];

    protected static array $middleware = ['eligible','consent'];

    protected string $onCompleteAction = RandomizePair::class;
}
