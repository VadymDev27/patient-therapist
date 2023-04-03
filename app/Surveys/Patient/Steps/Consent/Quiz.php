<?php

namespace App\Surveys\Patient\Steps\Consent;

use App\Surveys\Therapist\Steps\Consent\Quiz as ConsentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Surveys\Field;
use Surveys\Result;
use Surveys\SurveyStep;

class Quiz extends ConsentQuiz
{
    protected function correctAnswers(): array
    {
        return array_combine(static::fieldNames(),['True', 'True', 'False', 'True', 'True', 'True', 'True', 'True', 'True', 'True']);
    }

    protected static array $questions = [
        'This study focuses on teaching participants about getting and feeling safer and learning how to manage emotions, PTSD symptoms, and dissociation.',
        'Participants must be able to tolerate non-detailed references to: childhood and adult trauma; safety struggles and underlying reasons for these struggles; dissociation; and occasional brief discussions of “parts of self”.  ',
        'Only the patient will watch the educational videos.',
        'All therapist-patient pairs will be randomly assigned to either: 1) waiting six months before getting access to the study’s materials, or 2) gaining immediate access to the study’s materials. ',
        'The Patient and Therapist must each provide an email address to receive links to study materials and surveys. ',
        'The study is completely anonymous.  Names of participants will not be collected at any point in the study. ',
        'The TOP DD research team will not see or be able to access participants’ email addresses; the study’s website will automatically send emails with survey links at timed intervals so that the researchers will not manually send emails, nor see email addresses.  ',
        'If the Patient or Therapist decides to withdraw from the study, both members of the team must withdraw from the study. ',
        'Both participants must complete: (1) Screening Survey and (2) Initial Survey before beginning involvement with the TOP DD Network’s educational materials.  ',
        'Emails will be sent to participants with links to surveys every six months. ',
    ];
}
