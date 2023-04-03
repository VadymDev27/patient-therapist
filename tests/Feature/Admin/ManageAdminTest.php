<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
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

class ManageAdminTest extends TestCase
{
    use  WithFaker, WithoutMiddleware;

    public function test_make_new_admin()
    {
        $user = Admin::create([
            'email' => $this->faker->email(),
            'password' => Hash::make('password'),
            'is_admin' => true,
            'admin_permissions' => Arr::random(array_keys(Admin::PERMISSIONS), 2)
        ]);

        Notification::fake();

        $status = Password::broker('admins')->sendResetLink(
            ['email' => $user->email],
            fn ($user, $token) => $user->sendPasswordSetNotification($token)
        );

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, AdminInvitation::class);
    }

    public function test_token_is_added_to_db()
    {
        Notification::fake();

        $user = User::factory()->admin()->make();

        $response = $this->actingAs(Admin::master())
            ->post(route('admin.users.store'), [
                'email' => $user->email,
                'password' => 'password',
                'permissions' => $user->admin_permissions
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'is_admin' => true
        ]);
    }

    public function test_make_new_user_via_post()
    {
        Notification::fake();

        $user = User::factory()->admin()->make();

        $response = $this->actingAs(Admin::master())
            ->post(route('admin.users.store'), [
                'email' => $user->email,
                'password' => 'password',
                'permissions' => $user->admin_permissions
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users',[
            'email' => $user->email
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);
    }

    public function test_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->admin()->make();

        $response = $this->actingAs(Admin::master())
            ->post(route('admin.users.store'), [
                'email' => $user->email,
                'permissions' => $user->admin_permissions
            ]);

        $user = Admin::where('email',$user->email)->first();
        Notification::assertSentTo($user, AdminInvitation::class);
    }
}
