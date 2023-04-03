<?php

namespace Database\Factories;

use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Models\User;
use App\Surveys\Patient\InitialSurvey;
use App\Surveys\Patient\Steps\Milestone\DES;
use App\Surveys\Patient\Steps\Screening\PageTwo;
use App\Surveys\Therapist\DiscontinuationSurvey;
use App\Surveys\Therapist\FinalSurvey;
use App\Surveys\Therapist\SixMonthSurvey;
use App\Surveys\Therapist\WeeklySurvey;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Survey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'data' => [],
            'started_at' => now()->subMinutes(5)
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return ['completed_at' => now()];
        });
    }

    public function upToStep(int $step)
    {
        return $this->state(function (array $attributes) use ($step) {
            return  [
                'data' => array_merge($attributes['data'],['_progress' => array_fill_keys(range(0, $step), true)])
            ];
        });
    }

    public function type(string $type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return  [
                'type' => $type,
                'category' => $this->categoryFromType($type)
            ];
        });
    }

    private function categoryFromType(string $type): string
    {
        if (in_array($type, ['initial','initial-2','6-month','final'])) {
            return 'milestone';
        }

        if (Str::startsWith($type,'prep')) {
            return 'prep';
        }

        if (Str::endsWith($type, 'week')) {
            return 'weekly';
        }

        return $type;
    }

    public function completedConsentFor(string $type, ?User $user=null)
    {
        return $this->state(function (array $attributes) use ($type, $user) {
            $consentType = ($type === 'screening')
                    ? $type . '-consent-quiz'
                    : $type . '-consent';
            return ['completed_at' => now(),
                    'type' => $consentType,
                    'user_id' => $user ? $user->id : $attributes['user_id']];
        });
    }

    public function forPatient()
    {
        return $this->state(function (array $attributes) {
            return  [
                'user_id' => User::factory()->patient()
            ];
        });
    }

    public function eligiblePatientScreening()
    {
        return $this->state(function (array $attributes) {
            return  [
                'user_id' => User::factory()->patient(),
                'type' => 'screening',
                'data' => array_merge(
                    array_fill_keys(PageTwo::fieldNames(), 'Yes'),
                    ['_progress' => array_fill_keys(range(0,1), true)]
                )
            ];
        });
    }

    public function ineligiblePatientScreening()
    {
        return $this->state(function (array $attributes) {
            $fields = PageTwo::fieldNames();
            $yeses = Arr::random($fields, rand(1, count($fields)-1));
            $data = array_merge(
                Arr::except(array_fill_keys($fields, 'No'), $yeses),
                array_fill_keys($yeses, 'Yes')
            );
            return  [
                'user_id' => User::factory()->patient(),
                'type' => 'screening',
                'data' => array_merge(
                    $data,
                    ['_progress' => array_fill_keys(range(0,1), true)]
                )
            ];
        });
    }

    public function withFakeData(string $surveyClass)
    {
        return $this->state(['data' => $surveyClass::generateFakeData()]);
    }

    public function generateFakeData(string $surveyClass)
    {
        $fields = collect($surveyClass::fieldNames());
        $nulls = $fields->random(random_int(1,3));
        $data = $fields
            ->flatMap(fn ($item) =>
                [$item => $nulls->contains($item) ? null : $this->faker->word()])
            ->put('_progress', array_pad([], $surveyClass::lastStepIndex() + 1, true))
            ->toArray();

        if ($surveyClass === InitialSurvey::class) {
            $desData = collect(DES::fieldNames())
                ->flip()
                ->map(fn ($item) => 30)
                ->toArray();
            $data = array_merge($data, $desData);
        }

        return $data;
    }

    public function configure()
    {
        return $this->afterMaking(function (Survey $survey) {
            $survey->category = $survey->category ?: $survey->type;
        });
    }
}
