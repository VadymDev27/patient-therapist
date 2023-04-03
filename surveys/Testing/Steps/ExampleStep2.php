<?php

namespace Surveys\Testing\Steps;

class ExampleStep2 extends \Surveys\SurveyStep
{
    /**
     * Name of the step which is sent to the view.
     */
    protected string $title = 'New step';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'blade.template';

    /**
     * Names of all of the question fields (NOT including prefix)
     */
    protected array $fieldNames = ['Page2_Field1', 'Page2_Field2', 'Page2_Field3'];
}
