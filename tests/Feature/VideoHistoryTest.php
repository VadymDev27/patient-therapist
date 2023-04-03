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
use App\Models\Survey;
use App\Models\WeeklySettings;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Event;

use Tests\TestCase;

class VideoHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_see_menu_link_after_week_0()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 1]);

        $response = $this->actingAs($user)
                    ->get(route('dashboard'));

        $response->assertSee(route('videos.index'));
    }

    public function test_dont_see_menu_link_on_week_0()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 0]);

        $response = $this->actingAs($user)
                    ->get(route('dashboard'));

        $response->assertDontSee(route('videos.index'));
    }

    public function test_see_menu_link_after_prep_1()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $user = $pair->therapist();
        Survey::factory()
            ->state([
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'completed_at' => now()
                ])
            ->for($user)
            ->create();

        $response = $this->actingAs($user)
                    ->get(route('dashboard'));

        $response->assertSee(route('videos.index'));
    }

    public function test_can_view_video_history_after_week_0()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 1]);

        $response = $this->actingAs($user)
                    ->get(route('videos.index'));

        $response->assertViewIs('view-history');
    }

    public function test_cannot_view_video_history_on_week_0()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 0]);

        $response = $this->actingAs($user)
                    ->get(route('videos.index'));

        $response->assertForbidden();
    }

    public function test_can_view_video_history_after_1_prep_video()
    {
        $pair = Pair::factory()->randomized(false)->create();
        $user = $pair->therapist();
        Survey::factory()
            ->state([
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'completed_at' => now()
                ])
            ->for($user)
            ->create();

        $response = $this->actingAs($user)
            ->get(route('videos.index'));

        $response->assertOk();
        $response->assertViewIs('view-history');
        $response->assertSee(WeeklySettings::findByNumber(1,true)->video_title);
        $response->assertDontSee(WeeklySettings::findByNumber(2,true)->video_title);

    }

    public function test_cannot_view_video_history_before_prep()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => null]);

        $response = $this->actingAs($user)
                    ->get(route('videos.index'));

        $response->assertForbidden();
    }

    public function test_can_view_videos_before_week()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 8]);

        $video = WeeklySettings::findByNumber(6);

        $response = $this->actingAs($user)
                    ->get(route('videos.show', ['video' => $video->id]));

        $response->assertViewIs('rewatch-video');
        $response->assertSee($video->video_title);
    }

    public function test_cannot_watch_videos_from_future()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create(['week' => 8]);
        $video = WeeklySettings::findByNumber(9);

        $response = $this->actingAs($user)
                    ->get(route('videos.show', ['video' => $video->id]));

        $response->assertForbidden();
    }
}
