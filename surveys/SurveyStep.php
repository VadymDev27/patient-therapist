<?php

namespace Surveys;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Surveys\Trait\UsesFields;

abstract class SurveyStep
{
    use UsesFields;
    /**
     * Name of the  which is sent to the view.
     */
    protected string $title = 'New step';

    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'blade.template';

    protected AbstractSurvey $survey;
    protected int $index;

    protected static function fields(): array
    {
        return [];
    }

    public function init(AbstractSurvey $survey, int $index): self
    {
        $this->survey = $survey;
        $this->index = $index;

        return $this;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function viewName(): string
    {
        return $this->viewName;
    }

    public function isComplete(): bool
    {
        return (bool) $this->data("_progress.{$this->index}", false);
    }

    public function withFormData(): array
    {
        return $this->getViewData($this->data());
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return $this->survey->data($key, $default);
    }

    public function process(Request $request)
    {
        $data = $this->processData($request->all());
        return $this->handle($request, $data);
    }

    protected function handle(Request $request, array $payload): Result
    {
        return Result::success($payload);
    }

    public function details(): array
    {
        return [];
    }

}
