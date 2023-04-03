<?php

namespace App\Surveys\Patient\Milestone;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Patient\Steps\Milestone\BehaviorsChecklist;
use App\Surveys\Patient\Steps\Milestone\DERS;
use App\Surveys\Patient\Steps\Milestone\DES;
use App\Surveys\Patient\Steps\Milestone\DESrequired;
use App\Surveys\Patient\Steps\Milestone\PCLC;
use App\Surveys\Patient\Steps\Milestone\PITQ;
use App\Surveys\Patient\Steps\Milestone\SCS;
use App\Surveys\Patient\Steps\Milestone\WHOQOL;
use Surveys\AbstractSurvey;

class InitialSurvey extends AbstractSurvey
{
    use \App\Surveys\Trait\Milestone;

    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Patient Initial Survey';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'initial';

    public static string $role = 'patient';

    protected static array $stepClasses = [
        DERS::class,
        PCLC::class,
        DESrequired::class,
        BehaviorsChecklist::class,
        PITQ::class,
        SCS::class,
        WHOQOL::class
    ];

    protected string $onCompleteAction = RandomizePair::class;

    protected static array $middleware = ['eligible', 'consent'];
}
