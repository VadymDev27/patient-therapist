<?php

namespace Tests\Feature\Screening;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Surveys\Therapist\ScreeningSurvey as TherapistScreeningSurvey;
use Illuminate\Support\Arr;
use Surveys\Exception\CannotUpdateStepException;
use Tests\TestCase;

class TherapistScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_screening_survey()
    {
        /**
         * @var \Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('survey.therapist.screening.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.therapist.screening.page-one');
    }

    public function test_can_submit_screening_survey()
    {
        /** @var mixed */
        $user = User::factory()->create();
        Survey::factory()->completedConsentFor('screening')->for($user)->create();


        $response = $this->actingAs($user)
            ->post(
                route('survey.therapist.screening.store')
            );

        $response->assertStatus(302);
        $this->assertDatabaseHas('surveys', [
            'user_id' => $user->id,
            'type' => 'screening'
        ]);
    }

    public function test_appropriate_screening_page_shown()
    {
        $survey = Survey::factory()
            ->type('screening')
            ->upToStep(2)
            ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $response = $this->actingAs($survey->user)
            ->get(route('survey.therapist.screening.create'));

        $response->assertRedirect(route('survey.therapist.screening.show', ['step' => 3]));
    }

    public function test_cannot_submit_page_if_prev_not_completed()
    {
        $survey = Survey::factory()
            ->type('screening')
            ->upToStep(1)
            ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $testData = array_fill_keys(TherapistScreeningSurvey::fieldNames(3), 'test');

        //$this->expectException(CannotUpdateStepException::class);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.screening.update', ['step' => 3]),
                $testData
            );

        $response->assertViewIs('error');
    }

    public function test_survey_redirected_to_dashboard_after_last_step()
    {
        $survey = Survey::factory()
            ->type('screening')
            ->upToStep(3)
            ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $testData = array_fill_keys(TherapistScreeningSurvey::fieldNames(4), 'test');

        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.screening.update', ['step' => 4]),
                $testData
            );

        $response->assertRedirect('/dashboard');
    }

    public function test_can_access_completed_pages()
    {
        $survey = Survey::factory()
            ->type('screening')
            ->upToStep(3)
            ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $response = $this->actingAs($survey->user)
            ->get(route('survey.therapist.screening.show', ['step' => 1]));

        $response->assertViewIs('surveys.therapist.screening.page-two');
    }

    public function test_attempt_to_access_page_on_uncreated_survey_redirects_to_create_page()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::factory()->create();
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('survey.therapist.screening.show', ['step' => 1]));

        $response->assertRedirect(route('survey.therapist.screening.create'));

    }

    public function test_attempt_to_access_page_on_survey_redirects_to_first_incomplete_step()
    {
        $survey = Survey::factory()
            ->type('screening')
            ->upToStep(1)
            ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $response = $this->actingAs($survey->user)
            ->get(route('survey.therapist.screening.show', ['step' => 3]));

        $response->assertRedirect(route('survey.therapist.screening.show', ['step' => 2]));

    }

    public function test_patient_cannot_access_therapist_screening_survey()
    {
        $user = User::factory()->patient()->create();
        Survey::factory()->completedConsentFor('screening')->for($user)->create();

        $response = $this->actingAs($user)
            ->get(route('survey.therapist.screening.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_screening_marked_complete_after_last_step_submitted()
    {
        $survey = Survey::factory()
        ->type('screening')
        ->upToStep(3)
        ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $testData = array_fill_keys(TherapistScreeningSurvey::fieldNames(4), 'test');

        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.screening.update', ['step' => 4]),
                $testData
            );

        $this->assertTrue($survey->refresh()->isComplete());
    }

    public function test_completed_screening_throws_error()
    {
        $survey = Survey::factory()
                    ->type('screening')
                    ->completed()
                    ->create();
        Survey::factory()->completedConsentFor('screening')->for($survey->user)->create();

        $response = $this->actingAs($survey->user)
            ->get(
                route('survey.therapist.screening.create')
            );

        $response->assertViewIs('error');
    }
}
