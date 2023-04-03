<?php

namespace Surveys\Exception;

use Exception;
use \Surveys\SurveyStep;

class StepTemplateNotFoundException extends BaseException
{
    public function __construct(private SurveyStep $step)
    {
        parent::__construct('No template found for step ' . $this->step->index());
    }

    public static function forStep(SurveyStep $step): self
    {
        return new self($step);
    }
}
