<?php

namespace Tests\Feature\MilestoneSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;
use App\Surveys\Trait\UsesFinalWeek;
use Tests\TestCase;

class TherapistFinalSurveyTest extends TestCase
{
    use RefreshDatabase, UsesFinalWeek;

    public function test_can_see_final_week_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => static::finalWeek(), 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.final-week.create'));

        $response->assertOk();

        $response->assertViewIs('weekly.therapist.feedback');
        $response->assertSee('Weekly Activities - Week ' . static::finalWeek());
    }

    public function test_therapist_week_incremented()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => static::finalWeek(), 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->upToStep(1)->type('final-week')->create();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.final-week.update', ['step' => 2]));

        $therapist->refresh();
        $this->assertEquals(static::finalWeek() + 1, $therapist->week);
        $this->assertTrue($therapist->last_week_completed_at->copy()->addMinutes(5)->isAfter(now()));
    }

    public function test_redirected_to_dashboard()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => static::finalWeek(), 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->upToStep(1)->type('final-week')->create();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.final-week.update', ['step' => 2]));

        $response->assertRedirect(route('dashboard'));
    }
}
