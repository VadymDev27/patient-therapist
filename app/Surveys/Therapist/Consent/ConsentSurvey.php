<?php

namespace App\Surveys\Therapist\Consent;

use Surveys\AbstractConsent;
use App\Surveys\Therapist\Steps\Consent\PageOne;
use App\Surveys\Therapist\Steps\Consent\PageThree;
use App\Surveys\Therapist\Steps\Consent\PageTwo;
use App\Surveys\Therapist\Steps\Consent\Quiz;

class ConsentSurvey extends AbstractConsent
{
    public static string $title = 'Consent Form for Therapists';

    public static string $role = 'therapist';

    protected static array $stepClasses = [
        PageOne::class,
        PageTwo::class,
        PageThree::class,
    ];
}
