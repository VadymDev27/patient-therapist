<?php

namespace App\Models;

use App\Interfaces\UserInterface;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CanTimeTravel;

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function transform(): Authenticatable
    {
        if ($this)
        {
            if ($this->is_admin) {
                return Admin::find($this->id);
            }
        }

        return $this;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'is_therapist',
        'week',
        'last_week_completed_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
        'admin_permissions',
        'is_test',
        'test_time_travel',
        'test_can_go_ahead',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_therapist' => 'boolean',
        'is_eligible' => 'boolean',
        'last_week_completed_at' => 'immutable_datetime',
        'is_admin' => 'boolean',
        'admin_permissions' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('users', function (Builder $query) {
            $query->where('is_admin', false);
        });
    }

    public function logoutLink(): string
    {
        return (session('remember_web') && $this->isTest())
                ? route('test-users.logout')
                : route('logout');
    }

    public function summary(): array
    {
        return array_merge([
            'ID' => $this->id,
            'Pair ID' => $this->pair_id,
            'Role' => $this->role
            ],
            $this->randomized()
            ? [
            'Randomized' => $this->randomizationDate()->diffForHumans(),
            'Randomization group' => $this->waitlist() ? 'waitlist' : 'immediate access',
            'Week' => $this->week,
            'Finished last section' => $this->last_week_completed_at->diffForHumans(),
            ]
            : [
                'Eligible' => $this->isEligible() ? 'true' : 'false'
            ],
            [
                'Auto time travel' => $this->test_time_travel ? 'enabled' : 'disabled',
                'Outpacing allowed' => $this->test_can_go_ahead ? 'enabled' : 'disabled'
            ]
        );
    }

    public function incrementWeek()
    {
        $this->increment('week');
        $this->last_week_completed_at = now();

        if ($this->test_time_travel) {
            $this->timeTravelAllRelations(CarbonInterval::weeks(1));
        }


        $this->save();

        return $this;
    }

    public function timeTravelAllRelations(CarbonInterval $diff)
    {
        $this->timeTravel($diff);
        $this->surveys?->each->timeTravel($diff);
        $this->reminders?->each->timeTravel($diff);
        $this->analytics?->each->timeTravel($diff);
        $this->pair?->timeTravel($diff);
        $this->notifications?->each->timeTravel($diff);

        $this->refresh();

        return $this;
    }

    public function getRoleAttribute()
    {
        return $this->is_admin
            ? 'admin'
            : ($this->is_therapist ? 'therapist' : 'patient');
    }

    public function getGroupAttribute()
    {
        return $this->is_admin ? 'admin' : 'user';
    }

    public function waitlist(): bool | null
    {
        return $this->pair ? $this->pair->waitlist : null;
    }

    public function pair(): BelongsTo
    {
        return $this->belongsTo(Pair::class);
    }

    public function getCoParticipantName()
    {
        if ($this->is_therapist) {
            return ('patient');
        }
        return ('therapist');
    }

    public function getCoParticipant(): ?User
    {
        if ($this->pair) {
            return $this->pair->users()->where('is_therapist', !$this->is_therapist)->first();
        }
        return null;
    }

    public function isTest()
    {
        return $this->is_test;
    }

    public function isEligible()
    {
        return $this->is_eligible;
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function latestSurvey()
    {
        return $this->hasOne(Survey::class)->latestOfMany();
    }

    public function getSurvey(string $slug, mixed $week = null): ?Survey
    {
        return $this->surveys()
            ->where('type', $slug)
            ->where('week', $week)
            ->first();
    }

    public function hasCompletedSurvey(string $slug, ?int $week = null): bool
    {
        return $this->surveys()
            ->where('type', $slug)
            ->where('week', $week)
            ->whereNotNull('completed_at')
            ->exists();
    }

    public function getSurveyUrl(string $slug)
    {
        return route("survey.{$this->role}.{$slug}.create");
    }

    public function randomized(): bool
    {
        return $this->pair ? !is_null($this->pair->waitlist) : false;
    }

    public function randomizationDate(): CarbonImmutable | null
    {
        return $this->pair ? $this->pair->randomized_at : null;
    }

    public function getPrepVideoNumber(): ?int
    {
        if (!($this->week === 0 && $this->canAccessStudyContent())) {
            return null;
        }

        return $this->surveys()
            ->where('category', 'prep')
            ->whereNotNull('completed_at')
            ->count() + 1;
    }

    /**
     * If user is finished with waitlist or in immediate access group, returns true.
     * @return bool
     */
    public function canAccessStudyContent(): bool
    {
        if (is_null($this->waitlist())) {
            return false;
        }
        return $this->waitlist()
            ? $this->randomizationDate()->isBefore(now()->subMonths(6))
            : true;
    }

    public function canAccessWeeklyContent(): bool
    {
        if (is_null($this->last_week_completed_at)) {
            return false;
        }

        return !($this->inSevenDayWaitingPeriod() || $this->coParticipantIsBehind());
    }

    public function inSevenDayWaitingPeriod(): bool
    {
        return ($this->role === 'patient'
            && $this->week > 1
            && $this->last_week_completed_at->isAfter(now()->subDays(7)));
    }

    public function coParticipantIsBehind(): bool
    {
        if ($this->test_can_go_ahead)  {
            return false;
        }

        return $this->is_therapist
                    ? $this->week > $this->getCoParticipant()->week + 1   //therapist is allowed to get one week ahead
                    : $this->week > $this->getCoParticipant()->week;
    }

    /**
     * Returns the slug of the milestone survey that is due, or an empty string if participant is not due for a     milestone survey.
     * @return string
     */
    public function milestoneSurveyDue(): string
    {
        $surveys = [
            //conditions for those on the waitlist
            true => [
                '6' => 'initial-2',
                '12' => '6-month',
                '18' => 'final'
            ],
            //conditions for immediate access
            false => [
                '6' => '6-month',
                '12' => 'final'
            ],
            '' => [] //to account for null waitlist values
        ];
        $now = now();

        if (is_null($this->is_eligible)) {
            return 'screening';
        }

        if (
            $this->is_eligible
            && optional($this->getCoParticipant())->is_eligible
            && !$this->hasCompletedSurvey('initial')
        ) {
            return 'initial';
        }

        foreach ($surveys[$this->waitlist()] as $months => $slug) {
            if (
                $this->randomizationDate()->addMonths($months)->isBefore($now)
                && !$this->hasCompletedSurvey($slug)
            ) {
                return $slug;
            }
        }

        return '';
    }

    public function lastReminder()
    {
        return $this->hasOne(Reminder::class)->latestOfMany();
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytics::class);
    }

    public function discontinued()
    {
        return $this->pair ? $this->pair->discontinued : false;
    }

    public function accessExpired(): bool
    {
        return $this->expirationDate()?->isBefore(now()) ?? false;
    }

    public function expirationDate(): CarbonImmutable|null
    {
        return $this->randomizationDate()?->addMonths($this->waitlist() ? 18 : 12);
    }

    public function setScreenResult(bool $pass, ?string $reason = null, ?int $patient_id = null): User
    {
        $this->is_eligible = $pass;

        if ($this->is_therapist && ! $pass) {
            $survey = $this->getSurvey('screening');
            if ($survey) {
                $survey->updateData('fail_reason', $reason);
                $survey->updateData('patient_id', $patient_id);
            }
        }

        $this->save();

        return $this;
    }

    public function screenFailReason(): string
    {
        $survey = $this->getSurvey('screening');

        return $survey?->data('fail_reason');
    }

}
