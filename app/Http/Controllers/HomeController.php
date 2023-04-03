<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Surveys\Trait\UsesFinalWeek;
use App\View\MessagePage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use UsesFinalWeek;

    /**
     * Display the correct view depending on where the user is in the study.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        if ($user->discontinued()) {
            // Need discontinuation survey
            if (! $user->hasCompletedSurvey('discontinuation')) {
                return (new MessagePage)
                    ->line('Your coparticipant filled out the discontinuation survey -- OR, you indicated that you are no longer seeing this patient');
            }
            return (new MessagePage)
                    ->line('You have now discontinued your participation in the TOP DD Network RCT. Thank you for your involvement in the study.');
        }

        if ($user->milestoneSurveyDue()) {
            return $this->milestoneDue($user, $user->milestoneSurveyDue());
        }

        if ($user->accessExpired()) {
            return $this->expired($user);
        }

        if ($user->randomized()) {
            if ($user->canAccessStudyContent()) {
                return $this->studyContent($user);
            }

            return $this->waitlist($user);
        }

        if (! $user->is_eligible ) {
            return $this->screenFail($user);
        }

        if ($user->role === 'therapist') {
            return $this->waitingForPatient($user);
        }

        /** Participants waiting for co-participant to do initial survey */
        if ($user->hasCompletedSurvey('initial')) {
            return (new MessagePage)
                    ->line('Thank you for completing the Initial Survey!')
                    ->line("Your {$user->getCoParticipantName()} has not yet completed the Initial Survey. When they complete it, you will receive an email with additional information.");
        }
    }

    private function expired(User $user): Renderable
    {
        if ($user->is_therapist) {
            return (new MessagePage)
            ->line('Welcome back, and thank you for participating in the TOP DD Network RCT!')
            ->line('We hope this program has been helpful, and contained information that will continue to be helpful to you in your work with dissociative patients.');
        }
        return (new MessagePage)
            ->line('Welcome back, and thank you for participating in the TOP DD Network RCT!')
            ->line('We hope this program helped you find new ways to give yourself the care you need and deserve, and contained information that will continue to be helpful to you as you continue on your path of healing and recovery.')
            ->line('Thank you for participating in the TOP DD Network RCT, and please take good care of yourself – step by step, you’re getting there!')
            ->line('- The TOP DD Network RCT Team');
    }

    private function waitlist(User $user): Renderable
    {
        $date = $user->randomizationDate()->addMonths(6)->toFormattedDateString();
        return (new MessagePage)
            ->line('Thank you for completing the Initial Survey!')
            ->line('You have been randomized to be in the waitlist group, which allows us to determine the differential impact of participating in the online education program.')
            ->line('We look forward to welcoming you to begin the program!')
            ->line("You will gain access to the educational materials on {$date}.")
            ->line('Thank you for being a part of the TOP DD Network RCT!')
            ->line('The TOP DD Network RCT Team');
    }

    private function waitingForPatient(User $user): Renderable
    {
        /** Patient has not yet made an account */
        if (! $user->pair) {
            return (new MessagePage)
                ->line('Congratulations - Your answers indicate that you are eligible to complete the next step of the screening process!')
                ->line('We have sent you an email to forward to your patient.  This email includes a link that will enable your patient to create an account that is linked with yours in order to complete their part of the screening process')
                ->line('If you did not appear to receive this email, please click the button below and we will resend it.  (If you continue not to see the email, it is possible that email filtering has moved it to an unsolicited email / “spam” folder.  To prevent this problem, please search how to add [insert email address here] to your email programs’ “safe” / “safe senders” list.')
                ->statusText('invitation-link-sent','A new invitation link has been sent to the email address you provided during registration.')
                ->action('Resend Patient Invitation Email', route('patient-invitation.send'), 'POST')
                ->line('Note: Please do not forward this email to anyone other than the patient you described in your screening survey answers; doing so could result in someone other than your patient signing up as your study co-participant, and may result in being marked ineligible to participate.  Should the patient you nominated for inclusion opt to not participate, or screen as ineligible, you will be emailed information for how to nominate a different patient.');
        }

        /** Patient has made an account but has not yet completed screening */
        $patient = $user->getCoParticipant();
        if ($patient->milestoneSurveyDue() === 'screening') {
            return (new MessagePage)
                ->line('Thank you for participating in the TOP DD Network RCT')
                ->line('Your patient has not completed their screening survey yet.')
                ->line('Once they have completed the screening survey, the program will determine eligibility and send you an email.');
        }
    }

    private function prep(User $user): Renderable
    {
        $prepNumber = $user->getPrepVideoNumber();
        $url = $user->getSurveyUrl("prep-{$prepNumber}");
        if ($prepNumber === 1) {
            return (new MessagePage)
                ->line('Welcome to the TOP DD Network RCT educational program!')
                ->line('Click the button below to begin watching the Preparatory Videos')
                ->action('Start watching Preparatory Videos', $url);
        }
        return (new MessagePage)
                ->greeting('Welcome back!')
                ->line('Thank you for participating in the TOP DD Network RCT!')
                ->line('Click the "Watch Video" button below to watch the next Preparatory Video.')
                ->action('Watch Video', $url)
                ->line('To watch previous videos, click the "History" tab title above.');
    }

    private function studyContent(User $user): Renderable
    {
        $week = $user->week;

        /** Week 0: Prep Videos */
        if ($week === 0) {
            return $this->prep($user);
        }

        /** User cannot access weekly content */
        if ($user->inSevenDayWaitingPeriod()) {
            return (new MessagePage)
                    ->line('wait 7 days');
        }

        if ($user->coParticipantIsBehind()) {
            return (new MessagePage)
                ->line('coparticipant is behind');
        }

        /** Week 1 */
        if ($week === 1) {
            return $this->weekly($user->getSurveyUrl('first-week'), 'first topic\'s video and activities');
        }

        /** Weeks 2-30 */
        if ($week > 1 && $week < static::finalWeek()) {
            return $this->weekly($user->getSurveyUrl('weekly'), 'next activities');
        }

        /** Week 31 */
        if ($week === static::finalWeek()) {
            return $this->weekly($user->getSurveyUrl('final-week'), 'last activities');
        }

        /** Study Completed */
        if ($week > static::finalWeek()) {
            $date = $user->expirationDate()->toFormattedDateString();
            return (new MessagePage)
                ->greeting('Congratulations!')
                ->line('Congratulations: You have completed the TOP DD Network RCT program!')
                ->line('We hope this program has been helpful, and contained information that will continue to be helpful to you in your work with dissociative patients.')
                ->line("You will continue to have access to the materials on the site until {$date}, at which time you will also be receiving the final (12-month) milestone survey, which will help us assess the impact of participating in the program.")
                ->line('Thank you for participating in the TOP DD Network RCT!')
                ->line('- The TOP DD Network RCT Team');
        }
    }

    private function completed(User $user): Renderable
    {
        $date = $user->expirationDate()->toFormattedDateString();
        if ($user->is_therapist) {
            return (new MessagePage)
                ->greeting('Congratulations!')
                ->line('Congratulations: You have completed the TOP DD Network RCT program!')
                ->line('We hope this program has been helpful, and contained information that will continue to be helpful to you in your work with dissociative patients.')
                ->line("You will continue to have access to the materials on the site until {$date}, at which time you will also be receiving the final (12-month) milestone survey, which will help us assess the impact of participating in the program.")
                ->line('Thank you for participating in the TOP DD Network RCT!')
                ->line('- The TOP DD Network RCT Team');
        } else {
            return (new MessagePage)
                ->greeting('Congratulations!')
                ->line('We want to applaud you for dedicating time and effort to your healing and recovery and continuing to make use of the program, especially during those times it was really hard to do, and those times where you thought you might quit but decided not to after all.  We hope this program has helped you find new ways to give yourself the care you need and deserve, and that it will continue to be helpful to you as you continue on your path of healing and recovery.')
                ->line("You will continue to have access to the materials on the site until {$date}, at which time you will also be receiving the final (12-month) milestone survey, which will help us assess the impact of participating in the program.")
                ->line('Thank you for participating in the TOP DD Network RCT, and please take good care of yourself – step by step, you’re getting there!')
                ->line('- The TOP DD Network RCT Team');
        }
    }

    private function weekly(string $activityLink, string $activityDescription): Renderable
    {
        return (new MessagePage)
        ->greeting('Welcome back!')
        ->line('Thank you for participating in the TOP DD Network RCT!')
        ->line("Click the \"Next\" button below to access the {$activityDescription}.")
        ->action('Next', $activityLink)
        ->line('To access previous videos or exercises, click the "History" tab title above.');
    }

    private function screenFail(User $user): Renderable
    {
        if ($user->role === 'patient') {
            if ($user->hasCompletedSurvey('initial')) {
                return (new MessagePage)
                    ->line('You did not fill the whole DES');
            }
            return (new MessagePage)
                ->line('You failed screen');
        }

        switch ($user->screenFailReason()) {
            case 'diagnosis':
                return (new MessagePage)
                    ->line('Your patient did not meet diagnostic criteria');
            case 'treatment-duration':
                return (new MessagePage)
                    ->line('You havent been treating your patient long enough');
            case 'patient-fail':
                return (new MessagePage)
                    ->line('Your patient failed the screen');
            case 'des':
                return (new MessagePage)
                    ->line('Your patient filled the whole DES');
        }
    }

    private function milestoneDue(User $user, string $slug): Renderable
    {
        $url = $user->getSurveyUrl($slug);
        switch ($slug) {
            case 'screening':
                return (new MessagePage)
                    ->line('Thank you for your interest in the TOP DD Network RCT!')
                    ->line('Click the button below to access the screening survey.')
                    ->action('Take screening survey', $url);
            case 'initial':
                return (new MessagePage)
                    ->line('Thank you for signing up! The next step is to complete the Initial Survey')
                    ->action('Begin Initial Survey', $url);
            case 'initial-2':
                return (new MessagePage)
                    ->line('We are excited to welcome you to the Baseline Survey for the educational program portion of the TOP DD Network RCT!')
                    ->line($user->is_therapist
                        ? 'The following survey will help us understand how your patient is doing at the time of entry into (and before completing) the educational program.'
                        : 'The following survey will help us understand how you are doing as you enter this part of the study.')
                    ->line('Click below to begin the Baseline Survey.')
                    ->action('Begin Baseline Survey', $url);
            case '6-month':
                return (new MessagePage)
                    ->greeting('Welcome to the 6-month Milestone Survey for the TOP DD Network RCT!')
                    ->line('Your answers will help us continue to improve our ability to be of help to dissociative patients. Thank you!')
                    ->line('Click below to begin.')
                    ->action('Begin Milestone Survey', $url);
            case 'final':
                return (new MessagePage)
                    ->greeting('Welcome to the 12-month Milestone Survey for the TOP DD Network RCT!')
                    ->line($user->is_therapist
                        ? 'Your answers will help us assess the impact of participating in the program and continue to improve our ability to be of help to dissociative patients. Thank you!'
                        : 'Your answers will help us understand how you are doing and improve our ability to be of help to patients with dissociative symptoms.  Thank you!')
                    ->line('Click below to begin.')
                    ->action('Begin Milestone Survey', $url);
            default:
                return (new MessagePage)
                    ->line('Something went wrong. Oops!');
        }
    }
}
