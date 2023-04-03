<?php

namespace App\Surveys\Patient\Consent;

use App\Surveys\Patient\Consent\ConsentSurvey;
use Surveys\AbstractConsent;
use App\Surveys\Patient\Steps\Consent\PageOne;
use App\Surveys\Patient\Steps\Consent\PageThree;
use App\Surveys\Patient\Steps\Consent\PageTwo;
use App\Surveys\Patient\Steps\Consent\Quiz;

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
