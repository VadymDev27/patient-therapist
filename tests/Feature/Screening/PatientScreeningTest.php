<?php

namespace Tests\Feature\Screening;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Surveys\Action\ComputePatientEligibility;
use App\Surveys\Patient\ScreeningSurvey as PatientScreeningSurvey;
use App\Surveys\Therapist\ScreeningSurvey as TherapistScreeningSurvey;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Arr;
use Tests\TestCase;

class PatientScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_screening_survey()
    {
        $patient = User::factory()->patient()->create();
        Survey::factory()->completedConsentFor('screening')->for($patient)->create();

        $response = $this->actingAs($patient)
                        ->get(route('survey.patient.screening.create'));
        $response->assertViewIs('surveys.patient.screening.page-one');
    }

    public function test_can_submit_screening_survey()
    {
        /** @var mixed */
        $user = User::factory()->patient()->create();
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
            ->post(
                route('survey.patient.screening.store'),
            );


        $expect = array_merge(
            PatientScreeningSurvey::nullData(),
            ['_progress' => [true]]
        );
        $response->assertStatus(302);

        $this->assertDatabaseHas('surveys', [
            'user_id' => $user->id,
            'type' => 'screening',
        ]);
        $this->assertEquals($expect, $user->getSurvey('screening')->data); //this will equate null and false values. this is OK because there are other tests to ensure that multiselect options are false when the main field is null

    }

    public function test_can_complete_screening_survey()
    {
        $data = array_fill_keys(PatientScreeningSurvey::fieldNames(1), 'Yes');
        $survey = Survey::factory()
                    ->forPatient()
                    ->type('screening')
                    ->upToStep(0)
                    ->create();
        $user = $survey->user;
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
                ->post(route('survey.patient.screening.update',['step' => 1]),
                    $data
        );

        $this->assertTrue($user->refresh()->hasCompletedSurvey('screening'));
    }

    public function test_eligibility_calculation_action_correctly_calculates_eligibility()
    {
        $survey = Survey::factory()->eligiblePatientScreening()->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $this->assertTrue($survey->user->is_eligible);
    }

    public function test_eligibility_calculation_action_correctly_calculates_ineligibility()
    {
        $survey = Survey::factory()->ineligiblePatientScreening()->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $this->assertFalse($survey->user->is_eligible);
    }

    public function test_eligibility_calculated_as_true_for_eligible_survey_when_last_page_submitted()
    {
        $data = array_fill_keys(PatientScreeningSurvey::fieldNames(1), 'Yes');
        $survey = Survey::factory()
                    ->forPatient()
                    ->state([
                        'type' => 'screening',
                        'data' => array_merge(
                            PatientScreeningSurvey::nullData(),
                            ['_progress' => [true]]
                        )
                    ])
                    ->upToStep(0)
                    ->create();
        $user = $survey->user;
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
                ->post(route('survey.patient.screening.update',['step' => 1]),
                    $data
        );

        $this->assertTrue(is_null($user->is_eligible));
        $this->assertFalse(is_null($user->refresh()->is_eligible));
        $this->assertTrue($user->refresh()->is_eligible);
    }

    private function fakeWrongAnswers()
    {
        $questionNames = PatientScreeningSurvey::fieldNames(1);
        $wrongAnswers = array_rand(array_flip($questionNames), rand(2,count($questionNames)-1));
        $data = array_fill_keys(array_diff($questionNames, $wrongAnswers),'Yes') + array_fill_keys($wrongAnswers,'No');

        return $data;
    }

    public function test_eligibility_calculated_as_false_for_ineligible_survey_when_last_page_submitted()
    {
        $survey = Survey::factory()
                    ->forPatient()
                    ->type('screening')
                    ->upToStep(0)
                    ->create();
        $user = $survey->user;
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
                ->post(route('survey.patient.screening.update',['step' => 1]),
                    $this->fakeWrongAnswers()
        );
        $this->assertTrue(is_null($user->is_eligible));
        $this->assertFalse($user->refresh()->is_eligible);
    }
}
