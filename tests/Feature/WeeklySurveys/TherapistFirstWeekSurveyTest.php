<?php

namespace Tests\Feature\MilestoneSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;
use App\Surveys\Action\RandomizePair;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TherapistFirstWeekSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_first_week_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.first-week.create'));

        $response->assertOk();

        $response->assertViewIs('display-video');
    }

    public function test_cannot_see_first_week_survey_if_not_first_week()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 3, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.first-week.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_patient_cannot_see_therapist_first_week_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->patient())
            ->get(route('survey.therapist.first-week.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_therapist_week_incremented()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->type('first-week')->upToStep(0)->create(['week' => 1]);

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.first-week.update', ['step' => 1]));

        $this->assertEquals(2, $therapist->refresh()->week);
        $this->assertTrue($therapist->last_week_completed_at->copy()->addMinutes(5)->isAfter(now()));
    }

    public function test_data_stored_correctly()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.first-week.store'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $therapist->id,
            'type' => 'first-week',
            'category' => 'weekly',
            'week' => 1
        ]);
    }

    public function test_redirected_to_thank_you()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->type('first-week')->upToStep(0)->create(['week' => 1]);

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.first-week.update', ['step' => 1]));

        $response->assertRedirect(route('thank-you', ['slug' => 'weekly']));
    }

    public function test_can_access_survey_if_patient_is_one_week_behind()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 0, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        $therapist->increment('week');

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.first-week.create'));

        $response->assertViewIs('display-video');
        }

    public function test_can_access_survey_before_7_days_elapsed()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(6)]);
        $therapist = $pair->therapist();

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.first-week.create'));

        $response->assertViewIs('display-video');
    }
}
