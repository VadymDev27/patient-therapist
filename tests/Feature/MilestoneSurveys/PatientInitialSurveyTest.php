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

class PatientInitialSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_initial_survey()
    {
        $patient = User::factory()->eligible()->patient()->create();
        Survey::factory()->completedConsentFor('initial', $patient)->create();
        $response = $this->actingAs($patient)
            ->get(route('survey.patient.initial.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.patient.milestone.ders');
    }


    public function test_data_stored_correctly()
    {
        $patient = Pair::factory()->withEligibleUsers()->create()->patient();
        Survey::factory()->completedConsentFor('initial', $patient)->create();
        $response = $this->actingAs($patient)
            ->post(route('survey.patient.initial.store'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $patient->id,
            'type' => 'initial',
            'category' => 'milestone',
            'week' => null
        ]);
    }

    public function test_cannot_see_initial_survey_if_not_eligible()
    {
        $patient = User::factory()->patient()->create(['is_eligible' => false]);

        $response = $this->actingAs($patient)
            ->get(route('survey.patient.initial.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_therapist_cannot_see_patient_initial_survey()
    {
        $patient = User::factory()->eligible()->patient()->create();
        $therapist = $patient->getCoParticipant();
        Survey::factory()->completedConsentFor('initial', $therapist)->create();

        $response = $this->actingAs($therapist)
            ->get(route('survey.patient.initial.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_patient_is_randomized_if_therapist_already_completed_survey()
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

        $patient = $patient->refresh();
        $therapist = $therapist->refresh();
        $this->assertNotNull($patient->waitlist());
        $this->assertEquals(0, $patient->week);
        $this->assertEquals(0, $therapist->week);
        $this->assertNotNull($pair->refresh()->initial_des);
        $this->assertNull($pair->match_id);
    }

    public function test_both_participants_ineligible_if_des_missing_questions()
    {
        $pair = Pair::factory()
            ->has(User::factory()
                ->patient()
                ->eligible()
                ->withMilestoneSurveys(['initial', 'screening']))
            ->has(User::factory()
                ->eligible()
                ->withMilestoneSurveys(['initial', 'screening']))
            ->create();

        $therapist = $pair->therapist();
        $patient = $pair->patient();
        $survey = $patient->getSurvey('initial');
        $survey->updateData('DES_2', null);

        app()->make(RandomizePair::class)->execute($survey);

        $patient = $patient->refresh();
        $therapist = $therapist->refresh();
        $this->assertNull($patient->waitlist());
        $this->assertFalse($patient->is_eligible);
        $this->assertFalse($therapist->is_eligible);
        $this->assertEquals('des',$therapist->screenFailReason());
    }

    public function test_patient_not_randomized_if_therapist_not_yet_completed_survey()
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

        $this->assertNull($patient->refresh()->waitlist());
    }

    public function test_pair_is_correctly_matched()
    {
        $match = Pair::factory()->create(['initial_des' => 30, 'waitlist' => false]);

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

        $this->assertEquals($match->id, $pair->refresh()->match_id);
        $this->assertEquals($pair->id, $match->refresh()->match_id);
        $this->assertTrue($pair->waitlist);
    }

    public function test_pair_not_matched_if_des_score_too_different()
    {
        $match = Pair::factory()->create(['initial_des' => 100, 'waitlist' => false]);

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

        $this->assertNull($pair->refresh()->match_id);
    }

    public function test_pair_not_matched_if_discontinued()
    {
        $match = Pair::factory()->create(['initial_des' => 30, 'waitlist' => false, 'discontinued' => true]);

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

        $this->assertNull($pair->refresh()->match_id);
    }
}
