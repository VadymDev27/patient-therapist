<?php

namespace Tests\Feature\Notifications;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\SurveyCompleted;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;

use App\Notifications\WelcomeEmail;
use App\Listeners\SendWelcomeEmail;
use App\Models\User;
use App\Models\Pair;
use App\Notifications\PatientNotEligibleEmail;
use Tests\TestCase;

class PatientNotEligibleEmailTest extends TestCase
{
    public function test_patient_not_eligible_email_can_be_sent()
    {
        $user = User::factory()->create();

        Notification::fake();

        $user->notify(new PatientNotEligibleEmail);

        Notification::assertSentTo($user, PatientNotEligibleEmail::class);
    }
}
