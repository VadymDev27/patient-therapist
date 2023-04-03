<?php

namespace Surveys\Action;

use App\Models\Survey;

class NullAction extends SurveyAction
{
    public function execute(Survey $survey)
    {
    }
}
