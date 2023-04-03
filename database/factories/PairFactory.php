<?php

namespace Database\Factories;

use App\Models\Pair;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PairFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pair::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'waitlist' => null,
            'randomized_at' => null,
        ];
    }

    public function randomized(?bool $waitlist = null)
    {
        return $this->state(function (array $attributes) use ($waitlist) {
            return [
                'waitlist' => $waitlist ?? $this->faker->boolean(),
                'randomized_at' => now()
            ];
        });
    }

    public function waitlist()
    {
        return $this->state(function (array $attributes) {
            return [
                'waitlist' => true,
                'randomized_at' => now()->subMonths(random_int(0, 6))
            ];
        });
    }

    public function withUsers(array $therapist = [], array $patient = [])
    {
        return $this
            ->has(User::factory()
                ->count(2)
                ->state(new Sequence(
                    array_merge([
                        'is_therapist' => true,
                        'is_eligible' => true
                    ], $therapist),
                    array_merge([
                        'is_therapist' => false
                    ], $patient)
                )));
    }

    public function withEligibleUsers()
    {
        return $this
            ->has(User::factory()
                ->count(2)
                ->state(new Sequence(
                    [
                        'is_therapist' => true,
                        'is_eligible' => true
                    ],
                    [
                        'is_therapist' => false,
                        'is_eligible' => true
                    ]
                )));
    }

    public function configure()
    {
        return $this->afterCreating(function (Pair $pair) {
            if ($pair->users->isEmpty()) {
                if (is_null($pair->waitlist)) {
                    User::factory()->for($pair)->patient()->create();
                    User::factory()->for($pair)->eligible()->create();
                } else {
                    $therapist = User::factory()
                        ->for($pair)
                        ->eligible()
                        ->week(0)
                        ->create();
                    Survey::factory()->type('initial')->completed()->for($therapist)->create();

                    $patient = User::factory()
                        ->for($pair)
                        ->eligible()
                        ->week(0)
                        ->patient()
                        ->create();

                    Survey::factory()->type('initial')->completed()->for($patient)->create();
                }
            }
        });
    }

    public function createForWeek(int $week)
    {
        return $this->randomized(false)
            ->has(User::factory()
                ->count(2)
                ->has(Survey::factory()
                    ->type('initial')
                    ->completed())
                ->state(new Sequence(
                    ['is_therapist' => true],
                    ['is_therapist' => false],
                ))
                ->state([
                    'last_week_completed_at' => now()->subDays(8)
                ])
                ->eligible()
                ->week($week))
            ->create();
    }

    public function createTestForWeek(int $week, bool $waitlist=false)
    {
        return $this->randomized($waitlist)
            ->has(User::factory()
                ->count(2)
                ->has(Survey::factory()
                    ->type('initial')
                    ->completed())
                ->state(new Sequence(
                    ['is_therapist' => true],
                    ['is_therapist' => false],
                ))
                ->state([
                    'last_week_completed_at' => now()->subDays(8),
                    'is_test' => true,
                    'test_time_travel' => true,
                    'test_can_go_ahead' => true
                ])
                ->eligible()
                ->week($week))
            ->create();
    }


}
