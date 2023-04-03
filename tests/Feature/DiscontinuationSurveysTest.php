<?php

namespace Tests\Feature\ConsentSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Survey;
use App\Notifications\CoparticipantDiscontinued;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DiscontinuationSurveysTest extends TestCase
{
    use RefreshDatabase;

    public function test_therapist_can_see_survey()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                    ->get(route('survey.therapist.discontinuation.create'));

        $response->assertViewIs('surveys.therapist.discontinuation.page-one');
    }

    public function test_patient_can_see_survey()
    {
        /** @var \App\Models\User */
        $user = User::factory()->patient()->create();

        $response = $this->actingAs($user)
                    ->get(route('survey.patient.discontinuation.create'));

        $response->assertViewIs('surveys.patient.discontinuation.page-one');
    }

    public function test_route_registered()
    {
        $this->assertTrue(Route::has('survey.therapist.discontinuation.create'));
    }

    public function test_survey_completes_after_store()
    {
        /** @var \App\Models\User */
        $user = User::factory()->patient()->create();

        $response = $this->actingAs($user)
                    ->post(route('survey.patient.discontinuation.store'));

        $this->assertTrue($user->hasCompletedSurvey('discontinuation'));
    }

    public function test_data_stored_correctly()
    {
        /** @var \App\Models\User */
        $user = User::factory()->patient()->create();

        $response = $this->actingAs($user)
                    ->post(route('survey.patient.discontinuation.store'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $user->id,
            'type' => 'discontinuation',
            'category' => 'discontinuation',
            'week' => null
        ]);
    }

    public function test_pair_is_discontinued_and_unmatched()
    {
        $match = Pair::factory()->create();
        $pair = Pair::factory()->create(['match_id' => $match->id]);
        $match->match_id = $pair->id;
        $match->save();

        $response = $this->actingAs($pair->patient())
            ->post(route('survey.patient.discontinuation.store'));


        $this->assertNull($match->refresh()->match_id);
        $this->assertNull($pair->refresh()->match_id);
        $this->assertTrue($pair->discontinued);
    }

    public function test_coparticipant_is_sent_notification()
    {
        $match = Pair::factory()->create();
        $pair = Pair::factory()->create(['match_id' => $match->id]);
        $match->match_id = $pair->id;
        $match->save();

        Notification::fake();
        $response = $this->actingAs($pair->patient())
            ->post(route('survey.patient.discontinuation.store'));


        Notification::assertSentTo($pair->therapist(), CoparticipantDiscontinued::class);
    }

    public function test_pair_is_discontinued_and_unmatched_for_therapist()
    {
        $match = Pair::factory()->create();
        $pair = Pair::factory()->create(['match_id' => $match->id]);
        $match->match_id = $pair->id;
        $match->save();

        $response = $this->actingAs($pair->therapist())
            ->post(route('survey.therapist.discontinuation.store'));


        $this->assertNull($match->refresh()->match_id);
        $this->assertNull($pair->refresh()->match_id);
        $this->assertTrue($pair->discontinued);
    }

    public function test_patient_is_sent_notification()
    {
        $match = Pair::factory()->create();
        $pair = Pair::factory()->create(['match_id' => $match->id]);
        $match->match_id = $pair->id;
        $match->save();

        Notification::fake();

        $response = $this->actingAs($pair->therapist())
            ->post(route('survey.therapist.discontinuation.store'));



        Notification::assertSentTo($pair->patient(), CoparticipantDiscontinued::class);
    }
}
