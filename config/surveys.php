<?php

use App\Surveys\TestSurvey;

return [
    // List of all surveys
    'surveys' => [
        App\Surveys\Therapist\ScreeningSurvey::class,

        App\Surveys\Therapist\Prep\Prep1::class,
        App\Surveys\Therapist\Prep\Prep2::class,
        App\Surveys\Therapist\Prep\Prep3::class,

        App\Surveys\Therapist\Weekly\FirstWeek::class,
        App\Surveys\Therapist\Weekly\WeeklySurvey::class,
        App\Surveys\Therapist\Weekly\FinalWeek::class,

        App\Surveys\Therapist\Milestone\InitialSurvey::class,
        App\Surveys\Therapist\Milestone\InitialSurvey2::class,
        App\Surveys\Therapist\Milestone\SixMonthSurvey::class,
        App\Surveys\Therapist\Milestone\FinalSurvey::class,

        App\Surveys\Patient\ScreeningSurvey::class,

        App\Surveys\Patient\Prep\Prep1::class,
        App\Surveys\Patient\Prep\Prep2::class,
        App\Surveys\Patient\Prep\Prep3::class,

        App\Surveys\Patient\Weekly\FirstWeek::class,
        App\Surveys\Patient\Weekly\WeeklySurvey::class,
        App\Surveys\Patient\Weekly\FinalWeek::class,

        App\Surveys\Patient\Milestone\InitialSurvey::class,
        App\Surveys\Patient\Milestone\InitialSurvey2::class,
        App\Surveys\Patient\Milestone\SixMonthSurvey::class,
        App\Surveys\Patient\Milestone\FinalSurvey::class,


    ],

    // List of all consent surveys
    'consentSurveys' => [
        App\Surveys\Therapist\Consent\ConsentSurvey::class,
        App\Surveys\Patient\Consent\ConsentSurvey::class,
        App\Surveys\Therapist\Consent\ConsentQuizSurvey::class,
        App\Surveys\Patient\Consent\ConsentQuizSurvey::class
    ],

    // List of all single page surveys
    'singlePageSurveys' => [
        App\Surveys\Patient\DiscontinuationSurvey::class,
        App\Surveys\Therapist\DiscontinuationSurvey::class,

    ],
    // Middleware that gets added to all of the surveys
    'middleware' => ['web','auth:web'],

    // Video info
    'videos' => [

        'prep' => [

            'numVideos' => 3
        ],

        'weekly' => [

            'numVideos' => 30
        ]
    ]
];
