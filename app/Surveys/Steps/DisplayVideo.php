<?php

namespace App\Surveys\Steps;

use App\Models\WeeklySettings;
use Surveys\AbstractSurvey;
use Surveys\AbstractWeeklySurvey;
use Surveys\SurveyStep;

class DisplayVideo extends SurveyStep
{
    private function getWeek()
    {
        /** @var \Surveys\AbstractWeeklySurvey */
        $survey = $this->survey;
        return $survey->getWeek();
    }

    public function details(): array
    {
        $model = WeeklySettings::findByNumber($this->getWeek(), $this->survey->isPrep());
        return [
            'number' => $model->number,
            'prep' => $model->prep,
            'videoId' => $model->video_id,
            'videoTitle' => $model->video_title
        ];
    }

    protected string $viewName = 'display-video';

}
