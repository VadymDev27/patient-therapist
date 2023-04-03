<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WeeklySettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SurveyPreviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $surveys = [
            'Consent' => [
                'Page one' => 'page-one',
                'Page two' => 'page-two',
                'Page three' => 'page-three',
                'Quiz' => 'quiz'
            ],
            'Screening' => [
                'Demographics' => 'page-one',
                'Eligibility questions' => 'page-two'
            ],
            'Weekly' => [
                'Coping skills' => 'coping-skills',
                'Information sheet feedback' => 'information-sheet-feedback',
                'Practice exercises feedback' => 'practice-exercises-feedback',
                'Self kindness' => 'self-kindness',
                'Video feedback' => 'video-feedback',
                'Written exercises feedback' => 'written-exercises-feedback'
            ],
            'Milestone' => [
                'Behaviors checklist' => 'behaviors-checklist',
                'Difficulties in Emotion Regulation Scale' => 'd-e-r-s',
                'Dissociative Experiences Scale' => 'd-e-s',
                'PTSD Checklist - Civilian Version' => 'p-c-l-c',
                'Progress in Treatment Questionnaire' => 'p-i-t-q',
                'Program Development' => 'program-development',
                'Self Compassion Scale' => 's-c-s',
                'Study Feedback' => 'study-feedback',
                'WHO Quality of Life Scale' => 'w-h-o-q-o-l'
            ]
        ];


        return view('preview-surveys', ['surveyList' => $surveys]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklySettings  $weeklySettings
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $role, string $category, string $slug = '')
    {
        $step = $this->getStep($role, $category, $slug);
        return view($step->viewName(), [
            'step' => [
                'surveyTitle' => 'Preview Survey',
                'title' => $step->title(),
                'index' => 2,
                'category' => $category,
                'requiredFields' => $step->requiredFieldNames(),
                'mainFields' => $step->mainFieldNames(),
                'postUrl' => route('preview-survey.update', compact('role','category','slug')),
                'backUrl' => route('preview-survey.index'),
                'isLastStep' => false,
                'buttonMessage' => 'Mock Submit',
                'data' => [],
                'details' => [],
            ],
            'fields' => $step->getViewFieldInfo(),
        ]);
    }

    public function update(Request $request, string $role, string $category, string $slug = '')
    {
        $step = $this->getStep($role, $category, $slug);
        $request->session()->flash('_old_input', $step->getViewData($step->processData($request->all())));
        return redirect()->back();
    }

    private function getStep(string $role, string $category, string $step)
    {
        $stepName = collect(['App', 'Surveys', $role, 'Steps', $category, $step])
                    ->map(fn ($string) => Str::studly($string))
                    ->reject(fn ($string) => $string === '')
                    ->join('\\');
        return app()->make($stepName);
    }

}
