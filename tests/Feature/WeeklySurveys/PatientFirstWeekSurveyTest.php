<?php

namespace Tests\Feature\MilestoneSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;

use Tests\TestCase;

class PatientFirstWeekSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_first_week_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->patient())
            ->get(route('survey.patient.first-week.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.patient.weekly.self-kindness');
    }

    public function test_cannot_see_first_week_survey_if_not_first_week()
    {

        $pair = Pair::factory()->randomized(false)->create();

        $response = $this->actingAs($pair->patient())
            ->get(route('survey.patient.first-week.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_therapist_cannot_see_patient_first_week_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.patient.first-week.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_patient_week_incremented()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);
        $patient = $pair->patient();
        Survey::factory()->for($patient)->type('first-week')->upToStep(2)->create(['week' => 1]);

        $response = $this->actingAs($pair->patient())
            ->post(route('survey.patient.first-week.update', ['step' => 3]));

        $patient->refresh();
        $this->assertEquals(2, $patient->week);
        $this->assertTrue($patient->last_week_completed_at->copy()->addMinutes(5)->isAfter(now()));
    }

    public function test_cannot_access_survey_if_therapist_behind()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 0, 'last_week_completed_at' => now()->subDays(8)]);
        $patient = $pair->patient();
        $patient->increment('week');


        $response = $this->actingAs($patient)
            ->get(route('survey.patient.first-week.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_can_access_survey_before_7_days_elapsed()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(6)]);
        $patient = $pair->patient();

        $response = $this->actingAs($patient)
            ->get(route('survey.patient.first-week.create'));

        $response->assertViewIs('surveys.patient.weekly.self-kindness');
    }
}
