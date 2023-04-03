<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\View\MessagePage;
use GuzzleHttp\Psr7\Message;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class ThankYouController extends Controller
{
    public function __invoke(Request $request, string $slug)
    {
        $user = $request->user();

        return $this->getMessagePage($user, $slug)->subject('Thank You');
    }

    private function getMessagePage(User $user, string $slug): MessagePage
    {
        $isTherapist = $user->is_therapist;
        switch ($slug) {
            case 'prep':
                $url = $user->getSurveyUrl('prep-' . $user->getPrepVideoNumber());
                return $this->prep($url);
            case 'prep-final':
                $url = $user->getSurveyUrl('first-week');
                return $this->prepFinal($url);
            case 'weekly':
                $url = route('download.activities', ['number' => $user->week - 1]); //when they get to the thank you page, they have already completed the survey and their week count has been incremented, so they need the file from the week they just completed
                return $this->weekly($isTherapist, $url);
        }
        return (new MessagePage)->line('Placeholder thank you message');
    }

    private function weekly(bool $isTherapist, string $url): MessagePage
    {
        if ($isTherapist) {
            return (new MessagePage)
                ->line('Thank you for your feedback! Your feedback helps us know how we\'re doing and what to improve.')
                ->line("The button below will take you to this week's Written and Practice Exercises. Written Exercises are aimed at helping your patient apply the information discussed in the video to their life; Practice Exercises are designed to help your patient put what they're learning into practice.  Click on the button below to download the exercises your patient will be working on this week.")
                ->action('Download exercises', $url)
                ->line("Please Note: If you ever need to re-download an exercise or would like to re-watch a video, you can access these from a “History” link that is available once you log in.")
                ->line('See you next week!');
        }

        return (new MessagePage)
            ->line("Thank you for your feedback!  Your feedback helps us know how we're doing and what to improve.  Your feedback helps us be of better help to trauma patients, which means you are ultimately helping others as well as yourself.  Thank you!")
            ->line("The button will take you to this week's Written and Practice Exercises. Written Exercises will help you apply the information discussed in the video to your life. Practice Exercises are designed to help you put what you're learning into practice.  Click on the button below to download and begin working on this week's exercises.")
            ->action('Download exercises', $url)
            ->line("Please Note: If you ever need to re-download an exercise or would like to re-watch a video, you can access these from a “History” link that is available once you log in.")
            ->line('Take good care of yourself, and see you next week!');
    }

    private function prep(string $url): MessagePage
    {
        return (new MessagePage)
            ->line('Thank you for your feedback! Your feedback helps us know how we\'re doing and what to improve. This helps us be of better help to trauma patients.  Thank you!')
            ->line("You may now watch the :link or logout and return later.", 'next preparatory video', $url);
    }

    private function prepFinal(string $url): MessagePage
    {
        return (new MessagePage)
            ->line('Thank you for your feedback, and congratulations: You have finished watching the Preparatory Videos!')
            ->line('In these videos, we\'ve talked about the symptoms of PTSD and complex PTSD, and introduced some ways to help yourself get grounded.')
            ->line("You may now access the :link or logout and return later.", "first topic's materials", $url);
    }
}
