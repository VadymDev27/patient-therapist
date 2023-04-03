<?php

namespace Surveys\Action;

use App\Models\Survey;

abstract class SurveyAction
{
    abstract public function execute(Survey $survey);
}
