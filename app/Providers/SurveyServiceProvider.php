<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Surveys\Surveys;

class SurveyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Surveys::boot($this->app['config']['surveys']['surveys'],$this->app['config']['surveys']['consentSurveys'],$this->app['config']['surveys']['singlePageSurveys']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/surveys.php', 'surveys');
    }
}
