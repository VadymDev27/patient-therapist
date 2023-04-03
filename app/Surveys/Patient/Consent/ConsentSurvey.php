<?php

namespace App\Surveys\Patient\Consent;

use Surveys\AbstractConsent;
use App\Surveys\Patient\Steps\Consent\PageOne;
use App\Surveys\Patient\Steps\Consent\PageThree;
use App\Surveys\Patient\Steps\Consent\PageTwo;
use App\Surveys\Patient\Steps\Consent\Quiz;
use App\Surveys\Therapist\Consent\ConsentSurvey as TherapistConsentSurvey;

class ConsentSurvey extends TherapistConsentSurvey
{
    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Consent Form for Patients';

    public static string $role = 'patient';

    protected static array $stepClasses = [
        PageOne::class,
        PageTwo::class,
        PageThree::class,
    ];
}
