<?php

namespace Surveys\Testing;

class ExampleSurvey extends \Surveys\AbstractSurvey
{
    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Example title';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'example-slug';

    /**
     * Prefix to all of the question field names.
     */
    protected string $prefix = 'Example_';

    /**
     * Array of the steps in the survey (SurveyStep::class)
     */
    public array $steps = [Steps\ExampleStep::class, Steps\ExampleStep2::class];


}
