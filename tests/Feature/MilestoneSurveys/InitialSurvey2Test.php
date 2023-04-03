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

class InitialSurvey2Test extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_access_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $patient = $pair->patient();
        Survey::factory()->type('initial-2-consent')->completed()->for($patient)->create();

        $response = $this->actingAs($patient)->get(route('survey.patient.initial-2.create'));

        $response->assertViewIs('surveys.patient.milestone.ders');
    }

    public function test_data_stored_correctly()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $patient = $pair->patient();
        Survey::factory()->type('initial-2-consent')->completed()->for($patient)->create();

        $response = $this->actingAs($patient)->post(route('survey.patient.initial-2.store'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $patient->id,
            'type' => 'initial-2',
            'category' => 'milestone',
            'week' => null
        ]);
    }

    public function test_start_time_works()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $patient = $pair->patient();
        Survey::factory()->type('initial-2-consent')->completed()->for($patient)->create();

        $start = now();
        $this->actingAs($patient)->get(route('survey.patient.initial-2.create'));

        $this->actingAs($patient)->post(route('survey.patient.initial-2.store'));

        $survey = $patient->getSurvey('initial-2');

        $this->assertTrue($survey->started_at->closest($start, $survey->completed_at)->eq($start));
    }

    public function test_therapist_can_access_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->type('initial-2-consent')->completed()->for($therapist)->create();

        $response = $this->actingAs($therapist)->get(route('survey.therapist.initial-2.create'));

        $response->assertViewIs('surveys.therapist.milestone.patient-relationship');
    }

    public function test_redirected_if_not_time_for_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(5)]);
        $patient = $pair->patient();
        Survey::factory()->type('initial-2-consent')->completed()->for($patient)->create();

        $response = $this->actingAs($patient)->get(route('survey.patient.initial-2.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_redirected_if_not_waitlist()
    {
        $pair = Pair::factory()->create(['waitlist' => false, 'randomized_at' => now()->subMonths(8)]);
        $patient = $pair->patient();
        Survey::factory()->type('initial-2-consent')->completed()->for($patient)->create();

        $response = $this->actingAs($patient)->get(route('survey.patient.initial-2.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_redirected_if_not_yet_time()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(4)]);
        $patient = $pair->patient();

        $response = $this->actingAs($patient)->get(route('survey.patient.initial-2.create'));

        $response->assertRedirect(route('dashboard'));
    }
}
