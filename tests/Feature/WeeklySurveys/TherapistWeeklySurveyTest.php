<?php

namespace Tests\Feature\MilestoneSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;
use App\Models\User;
use App\Notifications\CoparticipantDiscontinued;
use App\Providers\RouteServiceProvider;
use App\Surveys\Action\MakeDiscontinuationSurvey;
use App\Surveys\Therapist\Weekly\WeeklySurvey;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Surveys\Exception\WeekOutOfBoundsException;
use Tests\TestCase;

class TherapistWeeklySurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_weekly_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.therapist.weekly.feedback');
        $response->assertSee('Weekly Activities - Week 10');
    }

    public function test_cannot_see_if_discontinued()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $pair->discontinue();

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_survey_is_stored_correctly()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        $data = ['TWF_1' => 'Test', 'TWF_2' => 'Another value'];
        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.store'), $data);

        $this->assertDatabaseHas('surveys', [
            'week' => 10,
            'user_id' => $therapist->id,
            'type' => 'weekly',
            'data' => json_encode(array_merge(
                WeeklySurvey::nullData(), $data, ['_progress' => [true]]
            ))
        ]);

    }

    public function test_can_access_second_step()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        $data = ['TWF_1' => 'Test', 'TWF_2' => 'Another value'];
        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), $data, ['_progress' => [true]]
                )
            ]
        )->create();

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.weekly.show', ['step' => 1]));

        $response->assertViewIs('surveys.therapist.weekly.feedback');
    }

    public function test_survey_second_step_stored_correctly()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        $data = ['TWF_1' => 'Test', 'TWF_2' => 'Another value'];
        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), $data, ['_progress' => [true]]
                )
            ]
        )->create();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 1]));

        $this->assertDatabaseHas('surveys', [
            'week' => 10,
            'user_id' => $therapist->id,
            'type' => 'weekly',
            'data' => json_encode(array_merge(
                WeeklySurvey::nullData(), $data, ['_progress' => [1 => true, 0 => true]]
            ))
        ]);
    }

    public function test_past_completed_weeks_do_not_interfere()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->for($therapist)->completed()->create(['week' => 9, 'type' => 'weekly']);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertOk();

        $response->assertViewIs('surveys.therapist.weekly.feedback');
        $response->assertSee('Weekly Activities - Week 10');
    }

    public function test_cannot_do_already_completed_survey()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->for($therapist)->completed()->create(['week' => 9, 'type' => 'weekly']);

        Survey::factory()->for($therapist)->completed()->create(['week' => 10, 'type' => 'weekly']);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertViewIs('error');
    }

    public function test_cannot_see_weekly_survey_if_under_min_week()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 1, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertViewIs('error');
    }

    public function test_cannot_see_weekly_survey_if_over_max_week()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 35, 'last_week_completed_at' => now()->subDays(8)]);

        $response = $this->actingAs($pair->therapist())
            ->get(route('survey.therapist.weekly.create'));

        $response->assertViewIs('error');
    }

    public function test_redirected_if_coparticipant_behind_2_weeks()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 7, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        $therapist->increment('week',2);

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.weekly.create'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_therapist_week_incremented()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 11, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->upToStep(3)->create(['type' => 'weekly', 'week' => 11]);

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 4]));

        $therapist->refresh();
        $this->assertTrue($therapist->hasCompletedSurvey('weekly',11));
        $this->assertEquals(12, $therapist->week);
        $this->assertTrue($therapist->last_week_completed_at->copy()->addMinutes(5)->isAfter(now()));
    }

    public function test_therapist_redirected_to_thank_you_page()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 11, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();
        Survey::factory()->for($therapist)->upToStep(3)->create(['type' => 'weekly', 'week' => 11]);

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 4]));

        $response->assertRedirect(route('thank-you', ['slug' => 'weekly']));
    }

    public function test_therapist_not_discontinued_if_does_not_fill_discontinuation()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 2]));


        $this->assertFalse($pair->refresh()->discontinued);
        $this->assertFalse($pair->therapist()->hasCompletedSurvey('discontinuation'));
    }


    public function test_therapist_redirect_to_next_step_if_does_not_fill_discontinuation()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 2]));


        $response->assertRedirect(route('survey.therapist.weekly.show', ['step' => 3]));
    }


    public function test_therapist_discontinued_if_does_fill_discontinuation()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $data = ['TIIF_1' => 'Strongly negative impact', 'TDS' => ['The study takes too much time for my patient to continue participating.'
        ]];

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 2]), $data);


        $this->assertTrue($pair->refresh()->discontinued);
        $this->assertTrue($pair->therapist()->hasCompletedSurvey('discontinuation'));
    }

    public function test_therapist_redirected_to_dashboard_if_discontinued()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $data = ['TIIF_1' => 'Strongly negative impact', 'TDS' => ['The study takes too much time for my patient to continue participating.'
        ]];

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.weekly.update', ['step' => 2]), $data);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_patient_gets_discontinued_notification()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $data = ['TIIF_1' => 'Strongly negative impact', 'TDS' => ['The study takes too much time for my patient to continue participating.'
        ]];


        Notification::fake();
        $response = $this->actingAs($therapist)
        ->post(route('survey.therapist.weekly.update', ['step' => 2]), $data);

        Notification::assertSentTo($pair->patient(),CoparticipantDiscontinued::class);
    }

    public function test_is_intervention_step()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 10, 'last_week_completed_at' => now()->subDays(8)]);
        $therapist = $pair->therapist();

        Survey::factory()->state(
            [
                'week' => 10,
                'user_id' => $therapist->id,
                'type' => 'weekly',
                'data' => array_merge(
                    WeeklySurvey::nullData(), ['_progress' => [1 => true, 0 => true]]
                )
            ]
        )->create();

        $response = $this->actingAs($therapist->refresh())
            ->get(route('survey.therapist.weekly.show', ['step' => 2]));

        $response->assertSuccessful();
        $response->assertViewIs('surveys.therapist.weekly.intervention-impact');
    }

}
