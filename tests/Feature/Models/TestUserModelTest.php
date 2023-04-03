<?php

namespace Tests\Feature\Models;

use App\Console\Commands\SendReminderEmails;
use App\Models\Pair;
use App\Models\Survey;
use App\Models\User;
use App\Notifications\Reminders\SixMonthSurveyReminder;
use App\Notifications\Reminders\WeeklyReminder;
use App\Surveys\Action\RandomizePair;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TestUserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_increment_week()
    {
        $pair = Pair::factory()->createTestForWeek(2);

        $pair->therapist()->incrementWeek();

        $this->assertTrue($pair->therapist()->canAccessWeeklyContent());
    }

    public function test_reminders()
    {
        $pair = Pair::factory()->createTestForWeek(2);

        $pair->therapist()->incrementWeek();


        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->therapist(), WeeklyReminder::class);
    }

    public function test_reminders_after_2_weeks()
    {
        $pair = Pair::factory()->createTestForWeek(2);

        $pair->therapist()->incrementWeek();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();
        $pair->therapist()->incrementWeek();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentToTimes($pair->therapist(), WeeklyReminder::class, 2);
    }

    public function test_time_travel()
    {
        $pair = Pair::factory()->createTestForWeek(2);
        $pair->therapist()->timeTravelAllRelations(CarbonInterval::months(6));

        $this->assertTrue($pair->refresh()->randomized_at->isBefore(now()->subMonths(6)));
        $this->assertEquals('6-month',$pair->therapist()->milestoneSurveyDue());
    }

    public function test_milestone_survey()
    {
        $pair = Pair::factory()->createTestForWeek(2);
        Notification::fake();

        $pair->therapist()->timeTravelAllRelations(CarbonInterval::months(6));
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->therapist(), SixMonthSurveyReminder::class);
    }

    public function test_does_not_match()
    {
        $match = Pair::factory()->create(['initial_des' => 30, 'waitlist' => false]);

        $pair = Pair::factory()
            ->has(User::factory()
                ->test()
                ->patient()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->has(User::factory()
                ->test()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->create();

        $therapist = $pair->therapist();
        $patient = $pair->patient();
        $survey = $patient->getSurvey('initial');

        app()->make(RandomizePair::class)->execute($survey);

        $this->assertNull($pair->refresh()->match_id);
        $this->assertNotNull($pair->waitlist);
    }

    public function test_others_do_not_match_to_test()
    {

        $test = Pair::factory()
            ->has(User::factory()
                ->test()
                ->patient()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->has(User::factory()
                ->test()
                ->eligible()
                ->withMilestoneSurveys(['initial']))
            ->create();

        app()->make(RandomizePair::class)->execute($test->patient()->getSurvey('initial'));


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
        $this->assertNull($test->refresh()->match_id);

        $this->assertNotNull($pair->waitlist);

    }
}
