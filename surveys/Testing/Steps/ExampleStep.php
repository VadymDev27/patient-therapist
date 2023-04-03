<?php

namespace Surveys\Testing\Steps;

class ExampleStep extends \Surveys\SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'New step';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'screening.patient.page-one';

    /**
     * Names of all of the question fields (NOT including prefix)
     */
    protected array $fieldNames = ['Field1', 'Field2', 'Field3'];
}
