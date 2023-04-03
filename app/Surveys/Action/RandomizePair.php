<?php

namespace App\Surveys\Action;

use App\Exceptions\DESMissingQuestions;
use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Models\User;
use App\Notifications\ImmediateAccessEmail;
use App\Notifications\WaitlistWelcome;
use App\Surveys\Patient\Steps\Milestone\DES;
use Illuminate\Support\Facades\Notification;

class RandomizePair extends SurveyAction
{
    public function execute(Survey $survey)
    {
        /** @var \App\Models\User */
        $participant = $survey->user;
        $coparticipant = $participant->getCoParticipant();

        if ($coparticipant->hasCompletedSurvey('initial')) {
            /** @var \App\Models\Pair */
            $pair = $participant->pair;

            try {
                $pair->assignGroup();
                $pair->users()->update(['week' => 0]);

                if ($pair->waitlist) {
                    Notification::send($pair->users, new WaitlistWelcome);
                } else {
                    Notification::send($pair->users, new ImmediateAccessEmail);
                }
            } catch (DESMissingQuestions $e) {
                $pair->therapist()->setScreenResult(false, 'des', $pair->patient()->id);
                $pair->patient()->setScreenResult(false);
            }
        }
    }

}
