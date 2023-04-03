<?php

namespace Tests\Feature\Screening;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;
use App\Surveys\Therapist\ScreeningSurvey;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ComputePatientEligibilityTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_ineligible_patient_notifies_therapist()
    {
        Notification::fake();

        $survey = Survey::factory()->ineligiblePatientScreening()->create();
        $patient = $survey->user;
        $therapist = $patient->getCoParticipant();
        $therapistSurvey = Survey::factory()
                                ->for($therapist)
                                ->type('screening')
                                ->withFakeData(ScreeningSurvey::class)
                                ->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        Notification::assertSentTo($therapist, PatientNotEligibleEmail::class);
    }

    public function test_eligible_patient_triggers_welcome_email()
    {
        Notification::fake();

        $survey = Survey::factory()->eligiblePatientScreening()->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $patient = $survey->refresh()->user;
        $therapist = $patient->getCoParticipant();

        Notification::assertSentTo($patient, WelcomeEmail::class);
        Notification::assertSentTo($therapist, WelcomeEmail::class);
    }

    public function test_ineligible_patient_makes_therapist_unpair()
    {
        $survey = Survey::factory()->ineligiblePatientScreening()->create();
        $patient = $survey->user;
        $therapist = $patient->getCoParticipant();
        $therapistSurvey = Survey::factory()
                                ->for($therapist)
                                ->type('screening')
                                ->withFakeData(ScreeningSurvey::class)
                                ->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $this->assertNull($therapist->refresh()->pair);
        $this->assertFalse($therapist->is_eligible);
    }

    public function test_ineligible_patient_sets_therapist_survey()
    {
        $survey = Survey::factory()->ineligiblePatientScreening()->create();
        $patient = $survey->user;
        $therapist = $patient->getCoParticipant();
        $therapistSurvey = Survey::factory()
                                ->for($therapist)
                                ->type('screening')
                                ->withFakeData(ScreeningSurvey::class)
                                ->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $this->assertEquals($patient->id, $therapistSurvey->refresh()->data('patient_id'));
        $this->assertEquals('patient-fail', $therapistSurvey->refresh()->data('fail_reason'));
    }

    public function test_ineligible_patient_unpaired()
    {
        $survey = Survey::factory()->ineligiblePatientScreening()->create();
        $patient = $survey->user;
        $therapist = $patient->getCoParticipant();
        $therapistSurvey = Survey::factory()
                                ->for($therapist)
                                ->type('screening')
                                ->withFakeData(ScreeningSurvey::class)
                                ->create();

        app()->make('App\Surveys\Action\ComputePatientEligibility')->execute($survey);

        $this->assertNull($patient->refresh()->pair);
    }
}
