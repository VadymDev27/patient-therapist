<?php

namespace Tests\Feature;

use App\Console\Commands\SendReminderEmails;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Pair;
use App\Models\Reminder;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Models\WeeklySettings;
use App\Notification\Reminders\WaitlistElapsedReminder;
use App\Notifications\Reminders\FinalSurveyReminder;
use App\Notifications\Reminders\Initial2SurveyReminder;
use App\Notifications\Reminders\SixMonthSurveyReminder;
use App\Notifications\Reminders\WaitlistReminder;
use App\Notifications\Reminders\WeeklyReminder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReminderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_users_who_can_get_off_waitlist()
    {
        $pair = Pair::factory()
            ->withEligibleUsers()
            ->state([
                'waitlist' => true,
                'randomized_at' => now()->subMonths(6)->subDay()
            ])
            ->create();



        $pair2 = Pair::factory()
            ->withEligibleUsers()
            ->state([
                'waitlist' => true,
                'randomized_at' => now()->subMonths(6)->subDay()
            ])
            ->create();

        Reminder::factory()
            ->for($pair2->patient())
            ->state([
                'type' => 'initial-2'
            ])->create();

        Pair::factory()
            ->withEligibleUsers()
            ->state([
                'waitlist' => true,
                'randomized_at' => now()->subMonths(2)
            ])
            ->create();
        Pair::factory()->randomized(false)->count(10)->create();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->patient(), Initial2SurveyReminder::class);
        Notification::assertNotSentTo($pair2->patient(), Initial2SurveyReminder::class);

        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->patient()->id,
            'type' => 'initial-2'
        ]);
    }

    public function test_users_who_need_2_month_reminder()
    {
        $pair = Pair::factory()
            ->withEligibleUsers()
            ->state([
                'waitlist' => true,
                'randomized_at' => now()->subMonths(2)->subDay()
            ])
            ->create();

        Reminder::factory()
            ->for($pair->patient())
            ->create([
                'type' => 'waitlist',
                'sent_at' => now()->subDay()
            ]);


        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();


        Notification::assertSentTo($pair->therapist(), WaitlistReminder::class);
        Notification::assertNotSentTo($pair->patient(), WaitlistReminder::class);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->therapist()->id,
            'type' => 'waitlist'
        ]);
    }

    public function test_users_who_need_4_month_reminder()
    {
        $pair = Pair::factory()
            ->withEligibleUsers()
            ->state([
                'waitlist' => true,
                'randomized_at' => now()->subMonths(4)->subDay()
            ])
            ->create();

        Reminder::factory()
            ->count(2)
            ->for($pair->patient())
            ->state(['type' => 'waitlist'])
            ->state(new  Sequence(
                ['sent_at' => now()->subMonths(2)->subDay()],
                ['sent_at' => now()->subDay()],
            ))
            ->create();

        Reminder::factory()
            ->for($pair->therapist())
            ->create([
                'type' => 'waitlist',
                'sent_at' => now()->subMonths(2)->subDay()
            ]);


        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();


        Notification::assertSentTo($pair->therapist(), WaitlistReminder::class);
        Notification::assertNotSentTo($pair->patient(), WaitlistReminder::class);

        $this->assertEquals(4, Reminder::where('type','waitlist')->count());
    }

    public function test_6_month_reminder_waitlist()
    {
        $pair = Pair::factory()
        ->withEligibleUsers()
        ->state([
            'waitlist' => true,
            'randomized_at' => now()->subMonths(12)->subDay()
        ])
        ->create();

        foreach ($pair->users as $user) {
        Survey::factory()
            ->type('initial-2')
            ->completed()
            ->for($user)
            ->create();
        }

        Survey::factory()
            ->type('6-month')
            ->completed()
            ->for($pair->patient())
            ->create();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->therapist(), SixMonthSurveyReminder::class);
        Notification::assertNotSentTo($pair->patient(), SixMonthSurveyReminder::class);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->therapist()->id,
            'type' => '6-month'
        ]);
    }

    public function test_6_month_reminder_nonwaitlist_randomization_date()
    {
        $pair = Pair::factory()
        ->withEligibleUsers()
        ->state([
            'waitlist' => false,
            'randomized_at' => now()->subMonths(6)->subDay()
        ])
        ->create();

        Survey::factory()
            ->type('6-month')
            ->completed()
            ->for($pair->patient())
            ->create();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->therapist(), SixMonthSurveyReminder::class);
        Notification::assertNotSentTo($pair->patient(), SixMonthSurveyReminder::class);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->therapist()->id,
            'type' => '6-month'
        ]);
    }

    public function test_12_month_reminder_non_waitlist()
    {
        $pair = Pair::factory()
        ->withEligibleUsers()
        ->state([
            'waitlist' => false,
            'randomized_at' => now()->subMonths(12)->subDay()
        ])
        ->create();


        foreach ($pair->users as $user) {
            Survey::factory()
                ->type('6-month')
                ->completed()
                ->for($user)
                ->create();
            }

        Survey::factory()
            ->type('final')
            ->completed()
            ->for($pair->patient())
            ->create();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentTo($pair->therapist(), FinalSurveyReminder::class);
        Notification::assertNotSentTo($pair->patient(), FinalSurveyReminder::class);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->therapist()->id,
            'type' => 'final'
        ]);
    }

    public function test_reminder_only_sent_once()
    {
        $pair = Pair::factory()
        ->withEligibleUsers()
        ->state([
            'waitlist' => false,
            'randomized_at' => now()->subMonths(12)->subDay()
        ])
        ->create();


        foreach ($pair->users as $user) {
            Survey::factory()
                ->type('6-month')
                ->completed()
                ->for($user)
                ->create();
            }

        Survey::factory()
            ->type('final')
            ->completed()
            ->for($pair->patient())
            ->create();

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();
        app()->make(SendReminderEmails::class)->handle();

        Notification::assertSentToTimes($pair->therapist(), FinalSurveyReminder::class);
        Notification::assertNotSentTo($pair->patient(), FinalSurveyReminder::class);
        $this->assertDatabaseHas('reminders', [
            'user_id' => $pair->therapist()->id,
            'type' => 'final'
        ]);
    }

    public function test_weekly_content_reminder()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 2, 'last_week_completed_at' => now()->subDays(6)]);

        Notification::fake();
        app()->make(SendReminderEmails::class)->handle();
        Notification::assertSentToTimes($pair->therapist(), WeeklyReminder::class);
        Notification::assertNotSentTo($pair->patient(), WeeklyReminder::class);
    }
}
