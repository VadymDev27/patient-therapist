<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\SurveyCompleted;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PatientInvitation;
use App\Listeners\SendPatientInvitation;
use App\Models\User;
use App\Models\Pair;
use App\Models\ScreeningSurvey;
use Illuminate\Support\Facades\Event;

use Tests\TestCase;

class PatientInvitationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_can_be_resent()
    {
        $user = User::factory()->eligible()->create();

        Notification::fake();
        $this->actingAs($user)
            ->post(route('patient-invitation.send'));

        Notification::assertSentTo($user, PatientInvitation::class);
    }

    public function test_notification_cannot_be_resent_by_paired_user()
    {
        $user = Pair::factory()->withUsers()->create()->therapist();

        $response = $this->actingAs($user)
            ->post(route('patient-invitation.send'));

        $response->assertForbidden();
    }
}
