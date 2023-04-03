<?php

namespace App\Surveys\Therapist;

use App\Models\Survey;
use App\Surveys\Action\ComputeTherapistEligibility;
use App\Surveys\Therapist\Steps\Screening\PageFive;
use App\Surveys\Therapist\Steps\Screening\PageFour;
use App\Surveys\Therapist\Steps\Screening\PageOne;
use App\Surveys\Therapist\Steps\Screening\PageThree;
use App\Surveys\Therapist\Steps\Screening\PageTwo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Surveys\AbstractSurvey;

class ScreeningSurvey extends AbstractSurvey
{
    public static string $role = 'therapist';
    /**
     * Display name of survey passed to view.
     */
    public static string $title = 'Therapist Screening Survey';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = 'screening';

    protected static array $stepClasses = [
        PageOne::class, PageTwo::class, PageThree::class, PageFour::class, PageFive::class
    ];

    protected string $onCompleteAction = ComputeTherapistEligibility::class;

    protected static array $middleware = ['consent:quiz'];

    private function clear(): void
    {
        $this->id = null;
        $this->data = [];
    }

    /**
     * Resets screening survey so that therapists can fill out patient-specific data again.
     * @todo put some protection on this route
     * @param Request $request
     * @param Survey $survey
     *
     * @return RedirectResponse
     */
    public function reset(Request $request): RedirectResponse
    {
        $this->resetSurvey($request->user()->getSurvey('screening'));
        $user = $request->user;
        $user->is_eligible = null;
        $user->save();

        return $this->renderer->redirect($this->steps[1], $this);
    }

    /**
     * Resets the screening survey so that therapists can do it again. Copies the first page of data so that they do not have to re-enter their personal information.
     * @param Survey $survey
     *
     * @return void
     */
    private function resetSurvey(Survey $survey): void
    {
        $survey->type = 'ineligible';
        $survey->save();
        $this->clear(); // make sure that exists() checks don't use the old survey as a pre-existing survey

        $step = $this->steps[0];

        $this->saveStepData($step, $step->processData($survey->data));
    }

}
