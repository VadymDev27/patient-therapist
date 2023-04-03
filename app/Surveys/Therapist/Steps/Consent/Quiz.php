<?php

namespace App\Surveys\Therapist\Steps\Consent;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Surveys\Field;
use Surveys\Result;
use Surveys\SurveyStep;

class Quiz extends SurveyStep
{
    protected string $viewName = 'surveys.therapist.consent.quiz';

    private int $minCorrect = 8;


    protected function handle(Request $request, array $payload): Result
    {
        return $this->grade($payload);
    }

    protected function correctAnswers(): array
    {
        return array_combine(static::fieldNames(),['True', 'True', 'False', 'True', 'True', 'True', 'True', 'False', 'True', 'False']);
    }

    private function grade(array $data): Result
    {
        $numCorrect = count(array_intersect_assoc($data, $this->correctAnswers()));
        $total = count($this->correctAnswers());

        if ($numCorrect < $this->minCorrect) {
            $message = "Sorry, in order to continue you are required to get {$this->minCorrect}/{$total} correct. You only had {$numCorrect}/{$total} correct. Please correct your answers and click the Submit button at the bottom of the page to continue.";
            return Result::failed($data,$message);
        }
        return Result::success($data);
    }

    protected static function fields(): array
    {
        return array_map(
            fn (string $name, string $question) =>
                Field::make($name)->question($question)->radio(['True','False']),
            appendStringToEach(range(1,10), 'CQ_'),
            static::$questions
        );
    }

    protected static array $questions = [
        'This study focuses on teaching participants about getting and feeling safer and learning how to manage emotions, PTSD symptoms, and dissociation.',
        'Participants must be able to tolerate non-detailed references to: childhood and adult trauma; safety struggles and underlying reasons for these struggles; dissociation; and occasional brief discussions of “parts of self”.  ',
        'Only the patient will watch the educational videos.',
        'All therapist-patient pairs will be randomly assigned to either: 1) waiting six months before getting access to the study’s materials, or 2) gaining immediate access to the study’s materials. ',
        'The Patient and Therapist must each provide an email address to receive links to study materials and surveys. ',
        'The study is completely anonymous.  Names of participants will not be collected at any point in the study. ',
        'The TOP DD research team will not see or be able to access participants’ email addresses; the study’s website will automatically send emails with survey links at timed intervals so that the researchers will not manually send emails, nor see email addresses.  ',
        'If the Patient or Therapist decides to withdraw from the study, the other member of the team may continue with the study. ',
        'Both participants must complete: (1) Screening Survey and (2) Initial Survey before beginning involvement with the TOP DD Network’s educational materials.  ',
        'Emails will be sent to participants with links to surveys every 12 months. ',
    ];
}
