<?php

namespace Surveys;

use App\Models\Survey;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\View\Factory;
use InvalidArgumentException;
use Surveys\Exception\StepTemplateNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class StepRenderer
{
    public function __construct(private Factory $factory)
    {
    }

    public function renderStep(SurveyStep $step, AbstractSurvey $survey, array $data = [])
    {
        //try {
            return $this->factory->make($step->viewName(), [
                'step' => $survey->getStepDetails($step),
                'requestData' => $data,
                'fields' => $step->getViewFieldInfo()
            ]);
/*         } catch (InvalidArgumentException) {
            throw StepTemplateNotFoundException::forStep($step);
        } */
    }

    public function redirect(SurveyStep $step, AbstractSurvey $survey)
    {
        if (!$survey->exists()) {
            return redirect($survey->url('create'));
        }

        return redirect($survey->url('show',$step->index()));
    }

    public function redirectWithError(SurveyStep $step, AbstractSurvey $survey, string $error)
    {
        if (!$survey->exists()) {
            return redirect($survey->url('create'))
                ->withErrors(['survey' => $error]);
        }

        return redirect($survey->url('show',$step->index()))
                ->withErrors(['survey' => $error]);
    }
}
