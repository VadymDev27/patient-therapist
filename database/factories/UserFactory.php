<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Pair;
use App\Models\Reminder;
use App\Models\Survey;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_therapist' => true,
            'is_admin' => false
        ];
    }

    public function admin()
    {
        return $this->state(
            [
                'is_therapist' => null,
                'is_admin' => true,
                'admin_permissions' => Arr::random(array_keys(Admin::PERMISSIONS), random_int(1,count(Admin::PERMISSIONS)))
            ]);
    }

    public function test()
    {
        return $this->state([
            'is_test' => true,
            'test_time_travel' => true,
            'test_can_go_ahead' => true
        ]);
    }

    public function patient()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_therapist' => false,
            ];
        });
    }

    public function eligible()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_eligible' => true,
            ];
        });
    }

    public function week(int $week)
    {
        return $this->state(['week' => $week]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (!$user->is_therapist && is_null($user->pair)) {
                $patient = $user;
                $therapist = User::factory()->eligible()->create();
                Pair::createFromUsers($patient, $therapist);
            }
        });
    }

    public function withReminders(array $reminderTypes)
    {
        return $this->has(
            Reminder::factory()
                ->count(count($reminderTypes))
                ->state(
                    new Sequence(
                        ...array_map(
                            fn ($type) => ['type' => $type],
                            $reminderTypes
                        )
                    )
                )
        );
    }

    public function withWeeklySurveys(int $week)
    {
        return $this->state(['week' => $week])
            ->has(
                Survey::factory()
                    ->count($week)
                    ->type('weekly')
                    ->completed()
                    ->state(new Sequence(...$this->generateWeekAttributeList($week)))
                    ->withFakeData($this->surveyClass('weekly'))
            )
            ->eligible();
    }

    public function withMilestoneSurveys(array $slugs, ?UserFactory $factory = null)
    {
        $factory = $factory ?? $this;
        $slug = array_pop($slugs);
        return $slug
            ? $factory->withMilestoneSurveys($slugs, $factory->withMilestoneSurvey($slug, $factory))
            : $factory;
    }

    private function withMilestoneSurvey(string $slug, UserFactory $factory)
    {
        return $factory->has(
            Survey::factory()
                ->type($slug)
                ->completed()
                ->withFakeData($this->surveyClass($slug))
                ->state(['category' => 'milestone'])
        );
    }

    private function generateWeekAttributeList(int $maxWeek): array
    {
        return collect(range(1, $maxWeek))->map(fn ($i) => ['week' => $i])->toArray();
    }

    private function surveyClass(string $slug): string
    {
        if ($this->raw()['is_therapist']) {
            switch ($slug) {
                case 'weekly':
                    return \App\Surveys\Therapist\Weekly\WeeklySurvey::class;
                case 'initial':
                    return \App\Surveys\Therapist\Milestone\InitialSurvey::class;
                case '6-month':
                    return \App\Surveys\Therapist\Milestone\SixMonthSurvey::class;
                case 'final':
                    return \App\Surveys\Therapist\Milestone\FinalSurvey::class;
                case 'screening':
                    return \App\Surveys\Therapist\ScreeningSurvey::class;
                case 'discontinuation':
                    return \App\Surveys\Therapist\DiscontinuationSurvey::class;
            }
        } else {
            switch ($slug) {
                case 'weekly':
                    return \App\Surveys\Patient\Weekly\WeeklySurvey::class;
                case 'initial':
                    return \App\Surveys\Patient\Milestone\InitialSurvey::class;
                case '6-month':
                    return \App\Surveys\Patient\Milestone\SixMonthSurvey::class;
                case 'final':
                    return \App\Surveys\Patient\Milestone\FinalSurvey::class;
                case 'screening':
                    return \App\Surveys\Patient\ScreeningSurvey::class;
                case 'discontinuation':
                    return \App\Surveys\Patient\DiscontinuationSurvey::class;
            }
        }
    }
}
