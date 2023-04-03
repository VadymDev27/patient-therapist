<?php

namespace Tests\Feature\MilestoneSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Surveys\Action\RandomizePair;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TherapistInitialSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_initial_survey()
    {
        $patient = User::factory()
            ->eligible()
            ->patient()
            ->create();
        $therapist = $patient->getCoParticipant();
        Survey::factory()->for($therapist)->completedConsentFor('initial')->create();
        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.initial.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.therapist.milestone.dx-update-pt-stressors');
    }

    public function test_data_stored_correctly()
    {
        $therapist = Pair::factory()->withEligibleUsers()->create()->therapist();
        Survey::factory()->for($therapist)->completedConsentFor('initial')->create();

        $response = $this->actingAs($therapist)->post(route('survey.therapist.initial.store'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $therapist->id,
            'type' => 'initial',
            'category' => 'milestone',
            'week' => null
        ]);
    }

    public function test_cannot_see_initial_survey_if_patient_not_eligible()
    {
        $patient = User::factory()->patient()->create(['is_eligible' => false]);
        $therapist = $patient->getCoParticipant();
        Survey::factory()->for($therapist)->completedConsentFor('initial')->create();

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.initial.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_patient_cannot_see_therapist_initial_survey()
    {
        $patient = User::factory()->eligible()->patient()->create();
        Survey::factory()->completedConsentFor('initial')->for($patient)->create();
        $therapist = $patient->getCoParticipant();

        $response = $this->actingAs($patient)
            ->get(route('survey.therapist.initial.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_therapist_is_randomized_if_patient_already_completed_survey()
    {
        $pair = Pair::factory()
            ->has(User::factory()
                ->patient()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->has(User::factory()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->create();

        $therapist = $pair->therapist();
        $patient = $pair->patient();
        $survey = $patient->getSurvey('initial');


        app()->make(RandomizePair::class)->execute($survey);

        $this->assertNotNull($therapist->refresh()->waitlist());
    }

    public function test_therapist_not_randomized_if_patient_not_yet_completed_survey()
    {
        $pair = Pair::factory()
            ->has(User::factory()
                ->patient()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->has(
                User::factory()
                    ->eligible()
                    ->has(Survey::factory()->type('initial'))
            )
            ->create();

        $therapist = $pair->therapist();
        $patient = $pair->patient();
        $survey = $patient->getSurvey('initial');

        app()->make(RandomizePair::class)->execute($survey);

        $this->assertNull($therapist->refresh()->waitlist());
    }
}
