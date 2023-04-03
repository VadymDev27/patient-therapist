<?php

namespace Surveys;

use Illuminate\Support\Facades\Route;

class Surveys
{

    public static function boot(array $surveys, array $consentSurveys, array $singlePageSurveys): void
    {
        $defaultMiddleware = config('surveys.middleware', []);
        foreach ($consentSurveys as $survey) {
            static::registerConsentRoutes($survey, 'survey', $defaultMiddleware);
        }
        foreach ($surveys as $survey) {
            static::registerRoutes($survey, 'survey', $defaultMiddleware);
        }
        foreach ($singlePageSurveys as $survey) {
            static::registerSinglePageSurveyRoutes($survey, 'survey', $defaultMiddleware);
        }


    }

    public static function registerRoutes(string $survey, string $routePrefix, array $defaultMiddleware): void
    {
        $middleware = array_merge($defaultMiddleware, $survey::middleware());

        Route::middleware($middleware)
            ->group(function () use ($survey, $routePrefix) {
                Route::get(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}",
                    "{$survey}@create"
                )->name("survey.{$survey::$role}.{$survey::$slug}.create");

                Route::post(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}",
                    "{$survey}@store"
                )->name("survey.{$survey::$role}.{$survey::$slug}.store");

                Route::get(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{step}",
                    "{$survey}@show"
                )->name("survey.{$survey::$role}.{$survey::$slug}.show");

                Route::post(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{step}",
                    "{$survey}@update"
                )->name("survey.{$survey::$role}.{$survey::$slug}.update");
            });
    }


    public static function registerSinglePageSurveyRoutes(string $survey, string $routePrefix, array $defaultMiddleware): void
    {
        $middleware = array_merge($defaultMiddleware, $survey::middleware());

        Route::middleware($middleware)
            ->group(function () use ($survey, $routePrefix) {
                Route::get(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}",
                    "{$survey}@create"
                )->name("survey.{$survey::$role}.{$survey::$slug}.create");

                Route::post(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}",
                    "{$survey}@store"
                )->name("survey.{$survey::$role}.{$survey::$slug}.store");
            });
    }

    public static function registerConsentRoutes(string $survey, string $routePrefix, array $defaultMiddleware): void
    {
        $middleware = array_merge($defaultMiddleware, $survey::middleware());

        Route::middleware($middleware)
            ->group(function () use ($survey, $routePrefix) {
                Route::get(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{slug}",
                    "{$survey}@create"
                )->name("survey.{$survey::$role}.{$survey::$slug}.create");

                Route::post(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{slug}",
                    "{$survey}@store"
                )->name("survey.{$survey::$role}.{$survey::$slug}.store");

                Route::get(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{step}/{slug}",
                    "{$survey}@show"
                )->name("survey.{$survey::$role}.{$survey::$slug}.show");

                Route::post(
                    "/{$routePrefix}/{$survey::$role}/{$survey::$slug}/{step}/{slug}",
                    "{$survey}@update"
                )->name("survey.{$survey::$role}.{$survey::$slug}.update");
            });
    }
}
