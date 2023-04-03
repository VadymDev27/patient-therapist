<?php

namespace App\Surveys\Patient;

use App\Surveys\Action\ComputePatientEligibility;
use App\Surveys\Patient\Steps\Screening\PageOne;
use App\Surveys\Patient\Steps\Screening\PageTwo;
use Surveys\AbstractSurvey;

class ScreeningSurvey extends AbstractSurvey
{
    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Patient Screening Survey';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'screening';

    public static string $role = 'patient';
    /**
     * Prefix to all of the question field names.
     */
    protected string $prefix = 'PSS_';

    protected static array $stepClasses = [
        PageOne::class,
        PageTwo::class,
    ];

    protected static array $middleware = ['consent:quiz'];

    protected string $onCompleteAction = ComputePatientEligibility::class;
}
