<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Survey;
use App\Notifications\AdminInvitation;
use App\Surveys\Patient\Steps\Consent\Quiz;
use Facade\IgnitionContracts\HasSolutionsForThrowable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class TestUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_index_page()
    {
        Pair::factory()->count(5)->createTestForWeek(5);

        $response = $this->actingAs(Admin::master())->get(route('test-users.index'));

        foreach (User::where('is_test', true)->get() as $user) {
            $response->assertSee($user->email);
        }
    }

    public function test_can_delete_user_and_coparticipant()
    {
        Pair::factory()->count(5)->createTestForWeek(5);
        $user = User::where('is_test',true)->first();
        $coparticipant = $user->getCoParticipant();
        $url = route('test-users.destroy', ['user' => $user->id]);
        $response = $this->actingAs(Admin::master())->delete($url);

        $response->assertSessionHas('status');
        $this->assertDeleted($user);
        $this->assertDeleted($coparticipant);
    }

    public function test_can_make_randomized_users()
    {
        $response = $this->actingAs(Admin::master())
                    ->post(route('test-users.store'), [
                        'waitlist' => '0',
                        'week' => '8',
                    ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => true,
            'week' => 8,
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => false,
            'week' => 8,
        ]);

        $pair = User::where('is_test',true)->first()->pair;

        $this->assertFalse($pair->waitlist);
    }

    public function test_can_make_waitlisted_users()
    {
        $response = $this->actingAs(Admin::master())
                    ->post(route('test-users.store'), [
                        'waitlist' => '1',
                        'week' => '8',
                    ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => true,
            'week' => 8,
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => false,
            'week' => 8,
        ]);

        $pair = User::where('is_test',true)->first()->pair;

        $this->assertTrue($pair->waitlist);
    }

    public function test_can_make_therapist_only()
    {
        $response = $this->actingAs(Admin::master())
        ->post(route('test-users.store'), [
            'patient_screening' => 'no',
            'therapist_screening' => 'no'
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => true,
            'week' => null,
            'is_eligible' => null
        ]);
    }

    public function test_can_make_patient_without_screening()
    {
        $response = $this->actingAs(Admin::master())
        ->post(route('test-users.store'), [
            'patient_screening' => 'no',
            'therapist_screening' => 'yes'
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => true,
            'week' => null,
            'is_eligible' => true
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => false,
            'week' => null,
            'is_eligible' => null
        ]);
    }

    public function test_can_make_eligible_pair()
    {
        $response = $this->actingAs(Admin::master())
        ->post(route('test-users.store'), [
            'patient_screening' => 'yes',
            'randomized' => 'no'
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => true,
            'week' => null,
            'is_eligible' => true
        ]);

        $this->assertDatabaseHas('users', [
            'is_test' => true,
            'is_therapist' => false,
            'week' => null,
            'is_eligible' => true
        ]);
    }
}
