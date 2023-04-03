<?php

namespace Tests\Feature\Models;

use App\Models\Pair;
use App\Models\Survey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_survey_url_patient_screening()
    {
        $user = User::factory()->patient()->create();

        $this->assertEquals(route('survey.patient.screening.create'), $user->getSurveyUrl('screening'));
    }

    public function test_get_survey_url_therapist_initial()
    {
        $user = User::factory()->create();

        $this->assertEquals(route('survey.therapist.initial.create'), $user->getSurveyUrl('initial'));
    }

    public function test_can_access_content_method_false_for_waitlist_too_soon()
    {
        $pair = Pair::factory()->create(['randomized_at' => now()->subMonths(5)->subDays(5), 'waitlist' => true]);

        $this->assertFalse($pair->therapist()->canAccessStudyContent());
    }

    public function test_can_access_content_method_true_for_waitlist_after_enough_time()
    {
        $pair = Pair::factory()->create(['randomized_at' => now()->subMonths(6)->subDays(5), 'waitlist' => true]);

        $this->assertTrue($pair->therapist()->canAccessStudyContent());
    }

    public function test_can_access_content_method_true_for_immediate_access()
    {
        $pair = Pair::factory()->create(['randomized_at' => now()->subMonths(5)->subDays(5), 'waitlist' => false]);

        $this->assertTrue($pair->therapist()->canAccessStudyContent());
    }

    public function test_can_access_weekly_content_if_both_weeks_the_same()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(8)]);

        $this->assertTrue($pair->therapist()->canAccessWeeklyContent());
    }

    public function test_can_access_weekly_content_if_coparticipant_ahead()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(8)]);
        $pair->therapist()->increment('week');

        $this->assertTrue($pair->patient()->canAccessWeeklyContent());
    }

    public function test_cannot_access_weekly_content_if_coparticipant_behind()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(8)]);
        $pair->patient()->increment('week');

        $this->assertFalse($pair->patient()->canAccessWeeklyContent());
    }

    public function test_therapist_behind_only_after_2_weeks()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(8)]);
        $pair->therapist()->increment('week');

        $this->assertTrue($pair->therapist()->canAccessWeeklyContent());

        $pair->therapist()->increment('week');
        $this->assertFalse($pair->therapist()->canAccessWeeklyContent());

    }

    public function test_therapist_can_access_weekly_content_before_7_days()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(6)]);

        $this->assertTrue($pair->therapist()->canAccessWeeklyContent());
    }

    public function test_patient_cannot_access_weekly_content_before_7_days()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 8, 'last_week_completed_at' => now()->subDays(6)]);

        $this->assertFalse($pair->patient()->canAccessWeeklyContent());
    }

    public function test_milestone_survey_for_waitlist()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);

        $this->assertEquals('initial-2', $pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_past_due_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(14)]);

        $this->assertEquals('initial-2', $pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_waitlist_6_month_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(14)]);
        Survey::factory()->type('initial-2')->for($pair->therapist())->completed()->create();

        $this->assertEquals('6-month', $pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_immediate_access()
    {
        $pair = Pair::factory()->create(['waitlist' => false, 'randomized_at' => now()->subMonths(8)]);

        $this->assertEquals('6-month', $pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_not_due()
    {
        $pair = Pair::factory()->create(['waitlist' => false, 'randomized_at' => now()->subMonths(7)]);
        Survey::factory()->type('6-month')->for($pair->therapist())->completed()->create();

        $this->assertEquals('', $pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_screening()
    {
        $user = User::factory()->create();

        $this->assertEquals('screening', $user->milestoneSurveyDue());
    }

    public function test_milestone_survey_for_initial()
    {
        $user = User::factory()->eligible()->patient()->create();
        $this->assertEquals('initial', $user->milestoneSurveyDue());
    }

    public function test_prep_video_number_waitlist()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(5)]);
        $user = $pair->patient();

        $this->assertEquals(null, $user->refresh()->getPrepVideoNumber());

    }

    public function test_prep_video_number_1()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $user = $pair->therapist();
        Survey::factory()
            ->state([
                    'type' => 'initial-2',
                    'category' => 'milestone',
                    'completed_at' => now()
                ])
            ->for($user)
            ->create();

        $this->assertEquals(1, $user->refresh()->getPrepVideoNumber());
    }

    public function test_prep_video_number_2()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $user = $pair->therapist();
        Survey::factory()
            ->state([
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'completed_at' => now()
                ])
            ->for($user)
            ->create();

        $this->assertEquals(2, $user->refresh()->getPrepVideoNumber());
    }

    public function test_prep_video_number_3()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $user = $pair->therapist();
        Survey::factory()
            ->count(2)
            ->state(new Sequence(
                [
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-2',
                    'category' => 'prep',
                    'completed_at' => now()
                ],
            ))
            ->for($user)
            ->create();

        $this->assertEquals(3, $user->refresh()->getPrepVideoNumber());
    }

    public function test_prep_video_number_3_when_partway_through() {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $user = $pair->patient();
        Survey::factory()
            ->count(4)
            ->state(new Sequence(
                [
                    'type' => 'initial-2',
                    'category' => 'milestone',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-2',
                    'category' => 'prep',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-3',
                    'category' => 'prep',
                    'data' => [
                        '_progress' => [ 0 => true]
                    ]
                ]
            ))
            ->for($user)
            ->create();

        $this->assertEquals(3, $user->refresh()->getPrepVideoNumber());

    }

    public function test_nonexpired_pair() {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(13)]);
        $user = $pair->patient();

        $this->assertFalse($user->accessExpired());
    }

    public function test_waitlist_expired_pair() {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(19)]);
        $user = $pair->patient();

        $this->assertTrue($user->accessExpired());
    }

    public function test_non_waitlist_expired_pair() {
        $pair = Pair::factory()->create(['waitlist' => false, 'randomized_at' => now()->subMonths(13)]);
        $user = $pair->patient();

        $this->assertTrue($user->accessExpired());
    }
}
