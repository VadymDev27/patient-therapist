<?php

namespace Surveys;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Support\Str;
use Surveys\AbstractSurvey;

abstract class AbstractConsent extends AbstractSurvey
{
    protected string $surveySlug;

    public static string $slug = 'consent';

    public function create(Request $request, ?string $slug=null)
    {
        $this->setSurveySlug($slug);
        return parent::create($request);
    }

    public function show(Request $request, string $step, ?string $slug=null)
    {
        $this->setSurveySlug($slug);
        return parent::show($request, $step);
    }

    public function store(Request $request, ?string $slug=null)
    {
        $this->setSurveySlug($slug);
        return parent::store($request);
    }

    public function update(Request $request, string $step, ?string $slug=null)
    {
        $this->setSurveySlug($slug);
        return parent::update($request,$step);
    }

    public function setSurveySlug(string $slug)
    {
        $isPermitted = $this->slugIsPermitted($slug);
        if (! $isPermitted) {
            abort(404,'The survey you are trying to access cannot be found');
        }
        $this->surveySlug = $slug;
    }

    protected function redirectTo(): string
    {
        $role = static::$role;

        return route("survey.{$role}.{$this->surveySlug}.create");
    }

    protected function slugIsPermitted(string $slug): bool
    {
        $role = static::$role;
        return Route::has("survey.{$role}.{$slug}.create");
    }

    public function url(string $method, ?int $step=null)
    {
        $slug = static::$slug;
        $role = static::$role;

        switch ($method) {
            case 'create':
                return route("survey.{$role}.{$slug}.create", ['slug' => $this->surveySlug]);
            case 'show':
                return route("survey.{$role}.{$slug}.show",
                    ['step' => $step, 'slug' => $this->surveySlug]);
            case 'store':
                return route("survey.{$role}.{$slug}.store", ['slug' => $this->surveySlug]);
            case 'update':
                return route("survey.{$role}.{$slug}.update",
                    ['step' => $step, 'slug' => $this->surveySlug]);
        }
    }

    public function getPrefix(): string
    {
        return Str::ucfirst($this->surveySlug) .'_';
    }
    protected function type()
    {
        return $this->surveySlug . '-' . static::$slug;
    }

    public static function middleware(): array
    {
        return array_merge(parent::middleware(), ['milestone']);
    }

    protected static function category(): string
    {
        return 'consent';
    }
}
