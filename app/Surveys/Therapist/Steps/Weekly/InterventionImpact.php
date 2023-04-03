<?php

namespace App\Surveys\Therapist\Steps\Weekly;

use App\Surveys\Therapist\DiscontinuationSurvey;
use App\Surveys\Therapist\Steps\Discontinuation;
use Illuminate\Http\Request;
use Surveys\Field;
use Surveys\Result;
use Surveys\SurveyStep;


class InterventionImpact extends SurveyStep
{
    private static array $options = [
        'Strongly negative impact',
        'Negative impact',
        'Somewhat negative impact',
        'No identifiable impact',
        'Somewhat positive impact',
        'Positive impact',
        'Strongly positive impact'];



    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.weekly.intervention-impact';

    protected static function fields(): array
    {
        return array_merge(
            [
                Field::make('TIIF_1')
                    ->radio(static::$options)
                    ->conditionalFields(['TDS','TDS_22_specify','TDS_Other'], static::$options[0]),
            ],
                Discontinuation::fields()
        );
    }


    public static function fieldNames(): array
    {
        return ['TIIF_1'];
    }

    protected function handle(Request $request, array $payload): Result
    {
        if (! is_null($payload['TDS'])) {
            app()->make(DiscontinuationSurvey::class)->store($request);
            return Result::redirect(route('dashboard'));
        }
        return Result::success($payload);
    }
}
