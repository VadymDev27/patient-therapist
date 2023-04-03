<?php

namespace App\Surveys\Therapist\Consent;

use Surveys\AbstractConsent;
use App\Surveys\Therapist\Steps\Consent\PageOne;
use App\Surveys\Therapist\Steps\Consent\PageThree;
use App\Surveys\Therapist\Steps\Consent\PageTwo;
use App\Surveys\Therapist\Steps\Consent\Quiz;

class ConsentQuizSurvey extends ConsentSurvey
{
    public static string $slug = 'consent-quiz';

    protected static array $stepClasses = [
        PageOne::class,
        PageTwo::class,
        PageThree::class,
        Quiz::class
    ];
}
