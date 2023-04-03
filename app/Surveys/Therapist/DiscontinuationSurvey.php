<?php

namespace App\Surveys\Therapist;

use App\Surveys\Action\DiscontinuePair;
use App\Surveys\Therapist\Steps\Discontinuation;
use App\Surveys\Therapist\Steps\Milestone\PatientRelationship;
use Surveys\AbstractSurvey;

class DiscontinuationSurvey extends AbstractSurvey
{
    public static string $role = 'therapist';

    public static string $slug = 'discontinuation';

    public static string $title = 'Discontinuation Survey';

    protected static array $stepClasses = [Discontinuation::class];

    protected static bool $discontinuation = true;

    protected string $onCompleteAction = DiscontinuePair::class;

}
