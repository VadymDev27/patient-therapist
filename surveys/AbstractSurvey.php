<?php

namespace Surveys;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Surveys\Exception\WeekOutOfBoundsException;
use Surveys\Exception\SurveyAlreadyCompletedException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Surveys\Exception\CannotUpdateStepException;
use Surveys\Exception\StepNotFoundException;
use Surveys\Exception\SurveyNotFoundException;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractSurvey
{
    /**
     * Display name of survey passed to view.
     */
    public static string $title = '';

    /**
     * Slug of survey used in URL and in database.
     */
    public static string $slug = '';

    public static string $role = '';


    protected static array $stepClasses = [];

    protected static function categorySteps(string $role = ''): array
    {
        return [];
    }

    /**
     * Category of survey for database; if empty string, will use slug instead.
     */
    protected static function category(): string
    {
        return '';
    }

    protected array $steps = [];

    protected string $onCompleteAction = Action\NullAction::class;
    /**
     * Survey's stored data
     */
    protected array $data = [];

    protected static array $middleware = [];
    /**
     * Survey's id in database
     */
    protected ?int $id = null;

    protected static bool $discontinuation = false;

    public function isPrep(): bool
    {
        return false;
    }

    protected function processLastStep(): Response | Responsable | Renderable
    {
        $this->markSurveyComplete();
        $model = Survey::find($this->id);

        app()->make($this->onCompleteAction)->execute($model);

        return $this->redirectAfterLastStep();
    }

    private function redirectAfterLastStep(): Response
    {
        return redirect()->to($this->redirectTo());
    }

    protected function redirectTo(): string
    {
        return route('dashboard');
    }

    public static function middleware(): array
    {
        return array_merge(static::$middleware, ['role:' . static::$role], static::$discontinuation ? [] : ['not-discontinued']);
    }

    public function title(): string
    {
        return static::$title;
    }
    public function __construct(protected StepRenderer $renderer)
    {
        $this->steps = collect(static::$stepClasses)
            ->map(fn ($step, $i) => app($step)->init($this, $i))
            ->all();
    }

    public function create(Request $request)
    {
        $request->session()->put(static::$slug . '-start', now());
        $step = $this->loadStep(0);
        if ($this->exists()) {
            return $this->renderer->redirect($this->firstIncompleteStep(), $this);
        }
        return $this->renderer->renderStep($step, $this);
    }

    public function show(Request $request, string $step)
    {
        $stepNumber = intval($step);
        try {
            $this->load();
        } catch (SurveyNotFoundException) {
            return redirect($this->url('create'));
        }
        $targetStep = $this->loadStep($stepNumber);

        if (!$this->stepCanBeEdited($targetStep)) {
            return $this->renderer->redirect($this->firstIncompleteStep(), $this);
        }
        $request->session()->flash('_old_input', $targetStep->withFormData());
        return $this->renderer->renderStep($targetStep, $this);
    }

    public function store(Request $request)
    {
        if ($this->exists()) {
            return abort(400);
        }

        $step = $this->loadStep(0);

        $result = $step->process($request);

        if (!$result->successful()) {
            return $this->renderer->redirectWithError(
                $step,
                $this,
                $result->error()
            );
        }

        $this->saveStepData($step, $result->payload());

        return $this->isLastStep($step) // check to see if is one page survey
            ? $this->processLastStep($step)
            : $this->renderer->redirect($this->steps[1], $this);
    }

    public function update(Request $request, string $step)
    {
        $stepNumber = intval($step);
        $this->load();

        $targetStep = $this->loadStep($stepNumber);

        if (!$this->stepCanBeEdited($targetStep)) {
            throw new CannotUpdateStepException();
        }

        $result = $targetStep->process($request);
        if (!$result->successful()) {
            return $result->isRedirect()
                ? $result->redirectResponse()
                : $this->renderer->redirectWithError(
                    $targetStep,
                    $this,
                    $result->error()
                );
        }

        $this->saveStepData($targetStep, $result->payload());

        return $this->isLastStep($targetStep)
            ? $this->processLastStep($targetStep)
            : $this->renderer->redirect($this->nextStep($targetStep), $this);
    }

    public function exists(): bool
    {
        if (!is_null($this->id)) {
            return true;
        }
        try {
            $this->load();
        } catch (SurveyNotFoundException) {
            return false;
        }
        return true;
    }

    public function url(string $method, ?int $step = null)
    {
        $slug = static::$slug;
        $role = static::$role;

        switch ($method) {
            case 'create':
                return route("survey.{$role}.{$slug}.create");
            case 'show':
                return route("survey.{$role}.{$slug}.show", ['step' => $step]);
            case 'store':
                return route("survey.{$role}.{$slug}.store");
            case 'update':
                return route("survey.{$role}.{$slug}.update", ['step' => $step]);
        }
    }

    protected function type()
    {
        return static::$slug;
    }

    public function getStepDetails(SurveyStep $step): array
    {
        return ([
            'surveyTitle' => $this->title(),
            'title' => $step->title(),
            'index' => $step->index(),
            'category' => static::category() ?: static::$slug,
            'requiredFields' => $step->requiredFieldNames(),
            'mainFields' => $step->mainFieldNames(),
            'postUrl' => $this->exists()
                ? $this->url('update', $step->index())
                : $this->url('store'),
            'backUrl' => $step->index() === 0
                ? ''
                : $this->url('show', $step->index() - 1),
            'isLastStep' => $this->isLastStep($step),
            'buttonMessage' => $this->isLastStep($step) ? 'Submit' : 'Next page >>',
            'data' => $step->withFormData(),
            'details' => $step->details()
        ]);
    }

    private function isLastStep(SurveyStep $step): bool
    {
        return $step->index() + 1 === count($this->steps);
    }

    /**
     * @return \App\Models\Survey
     * @throws Survey\Exception\SurveyAlreadyCompletedException
     */
    protected function load()
    {
        $survey = $this->getUserSurvey();
        if (is_null($survey)) {
            throw new SurveyNotFoundException();
        }
        if ($survey->isComplete()) {
            throw new SurveyAlreadyCompletedException();
        }
        $this->id = $survey->id;
        $this->data = $survey->data;
    }

    protected function getUserSurvey(): Survey | null
    {
        /**
         * @var  $user  \App\Models\User
         */
        $user = Auth::user();

        return $user->getSurvey($this->type(), $this->getWeek());
    }

    public function getWeek(): ?int
    {
        return null;
    }

    private function loadStep(int $stepNumber): SurveyStep
    {
        if ($stepNumber < 0 || $stepNumber >= count($this->steps)) {
            throw new StepNotFoundException();
        }
        return $this->steps[$stepNumber];
    }

    private function nextStep(SurveyStep $step): SurveyStep
    {
        return $this->loadStep($step->index() + 1);
    }

    private function stepCanBeEdited(SurveyStep $step)
    {
        if ($step->isComplete()) {
            return true;
        }

        return $step->index() === $this->firstIncompleteStep()->index();
    }


    private function firstIncompleteStep(): SurveyStep
    {
        return collect($this->steps)->first(fn (SurveyStep $step) => !$step->isComplete());
    }

    /**
     * Fetch any previously stored data for this survey.
     */
    public function data(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return Arr::except($this->data, '_progress');
        }
        return data_get($this->data, $key, $default);
    }

    protected function saveStepData(SurveyStep $step, array $data): void
    {
        $data['_progress'] =
            data_get($data, '_progress', []) +
            [$step->index() => true] +
            data_get($this->data, '_progress', []);

        if (!$this->exists()) {
            $this->createBlankSurvey(Auth::id());
        }
        $this->updateSurvey($data);
    }

    private function updateSurvey(array $data): void
    {
        $model = Survey::find($this->id);

        $model->data = collect($model->data)
            ->map(fn ($item, $key) => data_get($data, $key, $item))
            ->toArray();
        $model->save();
    }

    private function createBlankSurvey(int $userId): void
    {
        $model = Survey::create($this->initialData($userId));

        $this->id = $model->id;
    }

    private function markSurveyComplete(): void
    {
        $model = Survey::find($this->id);
        $model->completed_at = now();
        $model->save();
    }

    protected function initialData(int $userId): array
    {
        return [
            'user_id' => $userId,
            'type' => $this->type(),
            'data' => static::nullData(),
            'category' => static::category() ?: static::$slug,
            'started_at' => session()->pull(static::$slug . '-start') ?: now(),
            'week' => $this->getWeek()
        ];
    }

    public static function nullData(): array
    {
        return array_fill_keys(static::fieldNames(), null) + ['_progress' => []];
    }

    public static function fieldNames(?int $index = null): array
    {
        if (is_null($index)) {
            $steps = static::categorySteps(static::$role) ?: static::$stepClasses;
            return collect($steps)->flatMap(fn ($step) => $step::fieldNames())->toArray();
        }
        return static::$stepClasses[$index]::fieldNames();
    }

    public static function fakeData(int $index): array
    {
        return static::$stepClasses[$index]::fakeData();
    }

    public static function generateFakeData(): array
    {
        $data = collect(static::$stepClasses)
            ->map(fn ($step) => $step::generateFakeData())
            ->collapse(1)
            ->toArray();

        //this part makes sure everything is in the correct order for the database
        return collect(static::nullData())
            ->map(fn ($item, $key) => data_get($data, $key, $item))
            ->toArray();
    }

    public static function lastStepIndex(): int
    {
        return count(static::$stepClasses) - 1;
    }

    public function getUser(): User
    {
        return Auth::user();
    }

    public function getType(): string
    {
        return $this->type();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
