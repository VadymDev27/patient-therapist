<?php

namespace App\Models;

use App\Exceptions\DESMissingQuestions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Surveys\Patient\Steps\Milestone\DES;
use Illuminate\Database\Eloquent\Builder;

class Pair extends Model
{
    use HasFactory, CanTimeTravel;

    private int $matchBandSize = 5;
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'waitlist' => 'boolean',
        'discontinued' => 'boolean',
        'randomized_at' => 'immutable_datetime'
    ];

    public function isTest()
    {
        return $this->users()->where('is_test',true)->exists();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function therapist(): User
    {
        return $this->users()->where('is_therapist',true)->first();
    }

    public function patient(): User
    {
        return $this->users()->where('is_therapist',false)->first();
    }

    public static function createFromUsers(User $patient, User $therapist): self
    {
        $pair = self::create();
        $patient->pair()->associate($pair)->save();
        $therapist->pair()->associate($pair)->save();
        return $pair;
    }

    private function findMatch(): ?Pair
    {
        if ($this->isTest()) {
            return null;
        }

        return Pair::whereNull('match_id')
                    ->where('discontinued', false)
                    ->where('initial_des', '>', $this->initial_des - $this->matchBandSize)
                    ->where('initial_des', '<', $this->initial_des + $this->matchBandSize)
                    ->whereDoesntHave('users', function (Builder $query) {
                        $query->where('is_test',true);
                    })
                    ->first();
    }

    private function calculateInitialDES()
    {
        $data = $this->patient()->getSurvey('initial')->data;
        $answers = collect(DES::fieldNames())
                    ->map(fn ($field) => data_get($data, $field));

        throw_if($answers->containsStrict(null), DESMissingQuestions::class);
        return $answers->average();
    }

    public function assignGroup()
    {
        $this->initial_des = $this->calculateInitialDES();
        $match = $this->findMatch();
        if ($match) {
            $this->waitlist = ! $match->waitlist;
            $this->match_id = $match->id;
            $match->match_id = $this->id;
            $match->save();
        } else {
            $this->waitlist = random_int(0,1) === 0;
        }
        $this->randomized_at = now();
        $this->save();

        return $this;
    }

    public function match(): ?Pair
    {
        return Pair::find($this->match_id);
    }

    public function clearMatch(): void
    {
        $this->match_id = null;
        $this->save();
    }

    public function discontinue()
    {
        $this->discontinued = true;
        optional($this->match())->clearMatch();
        $this->clearMatch();

        return $this;
    }



}
