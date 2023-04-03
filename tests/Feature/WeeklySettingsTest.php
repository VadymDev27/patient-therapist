<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use App\Events\UserRegistered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Models\User;

use Tests\TestCase;

class WeeklySettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_participants_cannot_access_weekly_settings()
    {
        $response = $this->actingAs(User::all()->first()
            )->get(route('week.index'));

        $response->assertStatus(403);
    }
}
