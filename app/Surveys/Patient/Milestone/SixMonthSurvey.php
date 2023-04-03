<?php

namespace App\Surveys\Patient\Milestone;

use App\Surveys\Action\RandomizePair;
use App\Surveys\Patient\Steps\Milestone\BehaviorsChecklist;
use App\Surveys\Patient\Steps\Milestone\DERS;
use App\Surveys\Patient\Steps\Milestone\DES;
use App\Surveys\Patient\Steps\Milestone\PCLC;
use App\Surveys\Patient\Steps\Milestone\PITQ;
use App\Surveys\Patient\Steps\Milestone\SCS;
use App\Surveys\Patient\Steps\Milestone\WHOQOL;
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

    public static string $role = 'patient';

    protected static array $stepClasses = [
        DERS::class,
        PCLC::class,
        DES::class,
        BehaviorsChecklist::class,
        PITQ::class,
        SCS::class,
    ];

    protected static array $middleware = ['milestone:6-month', 'consent'];
}
