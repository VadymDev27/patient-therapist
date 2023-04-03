<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;


use Tests\TestCase;
use App\Events\UserRegistered;

use App\Models\User;
use App\Models\ScreeningSurvey;

class PatientRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_new_patient()
    {
        $therapist = User::factory()->create();
        $mockUser = User::factory()->make();
        $registerUrl = URL::signedRoute('register.patient', ['therapist' => $therapist->id]);
        $response = $this->post($registerUrl, [
            'email' => $mockUser->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $mockUser->email,
            'is_therapist' => 0
        ]);
    }

    public function test_patient_is_linked_to_therapist_after_registration()
    {
        $therapist = User::factory()->create();
        $mockUser = User::factory()->make();

        $registerUrl = URL::signedRoute('register.patient', ['therapist' => $therapist->id]);
        $response = $this->post($registerUrl, [
            'email' => $mockUser->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $user = User::where('email',$mockUser->email)->first();
        $this->assertFalse(is_null($user));
        $this->assertFalse(is_null($user->pair));
        $this->assertEquals($therapist->fresh(), $user->getCoParticipant());
    }
}
