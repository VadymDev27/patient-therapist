<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use App\Events\UserRegistered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Models\User;

use Tests\TestCase;

class TherapistRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertViewIs('auth.register');
    }

    public function test_can_register_therapist()
    {
        $user = User::factory()->make();
        $response = $this->post('/register', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'is_therapist' => true
        ]);
    }

    public function test_redirected_to_dashboard()
    {
        $user = User::factory()->make();

        $response = $this->post('/register', [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertRedirect('dashboard');
    }


}
