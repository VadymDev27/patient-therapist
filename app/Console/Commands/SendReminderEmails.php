<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notification\Reminders\WaitlistElapsedReminder;
use App\Notifications\Reminders\Initial2Reminder;
use App\Notifications\Reminders\FinalSurveyReminder;
use App\Notifications\Reminders\Initial2SurveyReminder;
use App\Notifications\Reminders\SixMonthSurveyReminder;
use App\Notifications\Reminders\WaitlistReminder;
use App\Notifications\Reminders\WeeklyReminder;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as NotificationClass;


class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to participants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Users who need 2 & 4 month reminders
        $this->handleNotifications($this->waitlistReminderUsers(), WaitlistReminder::class, 'waitlist');

        // Users who can begin content
        $this->handleNotifications($this->waitlistElapsedUsers(), Initial2SurveyReminder::class, 'initial-2');

        $this->handleNotifications($this->milestoneReminderUsers('6-month'), SixMonthSurveyReminder::class, '6-month');

        $this->handleNotifications($this->milestoneReminderUsers('final'), FinalSurveyReminder::class, 'final');

        $this->handleNotifications($this->weeklyUsers(), WeeklyReminder::class, 'weekly');

        return Command::SUCCESS;
    }

    private function handleNotifications(Collection $users, string $notificationClass, string $slug) {
        Notification::send($users, app()->make($notificationClass));
        $users->each(fn (User $user) => $user->reminders()->create(['type' => $slug]));
    }

    private function weeklyUsers()
    {
        return User::with('pair.users','reminders')
            ->where('week','>',0)
            ->whereDoesntHave('reminders', function (Builder $query) {
                $query->where('sent_at','>', now()->subDays(7));
            })
            ->get()
            ->filter
            ->canAccessWeeklyContent();
    }

    private function waitlistReminderUsers()
    {
        return User::with('pair', 'reminders')
        ->whereHas('pair', function (Builder $query) {
            $query
                ->where('waitlist', true)
                ->where('randomized_at', '>', now()->subMonths(6))
                ->where('randomized_at', '<', now()->subMonths(2));
        })->whereDoesntHave('reminders', function (Builder $query) {
            $query->where('type', 'waitlist')
                ->where('sent_at', '>', now()->subMonths(2));
        })->get();
    }

    private function waitlistElapsedUsers()
    {
        return User::with('surveys', 'pair', 'reminders')->whereHas('pair', function (Builder $query) {
            $query
                ->where('waitlist', true)
                ->where('randomized_at', '<=', now()->subMonths(6));
        })->whereDoesntHave('surveys', function (Builder $query) {
            $query->where('type', 'initial-2');
        })->whereDoesntHave('reminders', function (Builder $query) {
            $query->where('type', 'initial-2');
        })->get();
    }

    private function milestoneReminderUsers(string $slug)
    {
        $interval = ($slug === '6-month') ? new CarbonInterval(0,6) : new CarbonInterval(0,12);
        return User::with('surveys', 'pair', 'reminders')
            ->where(function (Builder $query) use ($interval) {
                $query->whereHas('pair', function (Builder $query) use ($interval) {
                    $query
                        ->where('waitlist', true)
                        ->where('randomized_at', '<=', now()->sub($interval)->subMonths(6));
                })->orWhereHas('pair', function (Builder $query) use ($interval) {
                    $query
                        ->where('waitlist', false)
                        ->where('randomized_at', '<=', now()->sub($interval));
                });
            })
            ->whereDoesntHave(
                'surveys',
                function (Builder $query) use ($slug) {
                    $query->where('type', $slug);
                }
            )->whereDoesntHave('reminders', function (Builder $query) {
                $query->where('sent_at','>', now()->subDays(7));
            })->get();
    }
}
