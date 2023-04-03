<?php

namespace App\Surveys\Therapist\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class StudyFeedback extends SurveyStep
{
    /**
     * Name of the view which is used to find the Blade file.
     */
    protected string $viewName = 'surveys.therapist.milestone.study-feedback';

    protected static function fields(): array
    {
        return ([
            Field::make('ISF_1')->radio($impactScale = [
                'strongly negative impact',
                'negative impact',
                'somewhat negative impact',
                'no identifiable impact',
                'somewhat positive impact',
                'positive impact',
                'strongly positive impact'
            ])->question("How would you rate the TOP DD Network RCT’s impact on your patient?"),
            Field::make('ISF_2')
                ->radio($impactScale)
                ->question("How would you rate the TOP DD Network RCT’s impact on your relationship with your patient?"),
            Field::make('ISF_3')->radio([
                'way too few',
                'too few',
                'just right',
                'too many',
                'way too many'
            ])->question("How did you feel about the number of videos?"),
            Field::make('ISF_4')->radio($lengthScale = [
                'way too short',
                'too short',
                'just  right',
                'too long',
                'way too long'
            ])->question("How did you feel about the length of the videos?"),
            Field::make('ISF_5')->radio([
                'way too slow',
                'too slow',
                'just  right',
                'too fast',
                'way too fast'
            ])->question("How did you feel about the pace of the TOP DD Network RCT?"),
            Field::make('ISF_6')
                ->radio($lengthScale)
                ->question("How did you feel about the length of the TOP DD Network RCT?"),
            Field::make('ISF_7')->radio([
                'no; no new information',
                'some learning',
                'good amount of learning',
                'significant amount of learning',
                'learned a great deal'
            ])->question("Did you learn new information about treating DD patients from this study?"),
            Field::make('ISF_8')->question('What could we do to improve this intervention?'),
            Field::make('ISF_9')->question('Could you tell us what (if anything) you found helpful or useful about being involved in this study?')
        ]);
    }
}
