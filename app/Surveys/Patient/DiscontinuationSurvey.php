<?php

namespace App\Surveys\Patient;

use App\Surveys\Action\DiscontinuePair;
use App\Surveys\Patient\Steps\Discontinuation;
use App\Surveys\Therapist\DiscontinuationSurvey as TherapistDiscontinuationSurvey;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use Surveys\AbstractSurvey;

class DiscontinuationSurvey extends TherapistDiscontinuationSurvey
{
    public static string $role = 'patient';

    protected static array $stepClasses = [Discontinuation::class];
}
