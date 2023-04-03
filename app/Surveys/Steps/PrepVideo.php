<?php

namespace App\Surveys\Steps;

use App\Models\WeeklySettings;
use Surveys\Exception\StepNotFoundException;
use Surveys\SurveyStep;

class PrepVideo extends SurveyStep
{
    protected int $number;

    protected string $videoId;

    protected string $videoTitle;

    public function setNumber(int $number)
    {
        $model = WeeklySettings::findByNumber($number,true);
        throw_if(is_null($model), StepNotFoundException::class);
        $this->videoId = $model->video_id;
        $this->videoTitle = $model->video_title;
        $this->number = $number;

        return $this;
    }

    public function details(): array
    {
        return [
            'number' => $this->number,
            'prep' => true,
            'videoId' => $this->videoId,
            'videoTitle' => $this->videoTitle
        ];
    }

    protected string $viewName = 'display-video';
}
