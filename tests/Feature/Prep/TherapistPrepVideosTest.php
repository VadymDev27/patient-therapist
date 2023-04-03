<?php

namespace Tests\Feature\Prep;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Pair;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Models\WeeklySettings;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class TherapistPrepVideosTest extends TestCase
{
    use RefreshDatabase;
    protected string $role = 'therapist';

    private function getParticipant(Pair $pair) {
        return $pair->refresh()->users->sole(fn (User $user) => $user->role === $this->role);
    }

    public function test_can_view_prep_1()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $user = $this->getParticipant($pair);

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-1.create"));

        $response->assertSuccessful();
    }

    public function test_cannot_view_prep_videos_after_week_0()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $pair->users()->update(['week' => 2]);
        $user = $this->getParticipant($pair);

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-1.create"));

        $response->assertStatus(403);
        $response->assertViewIs('error');
    }

    public function test_cannot_view_prep_after_one_not_yet_completed()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $user = $this->getParticipant($pair);

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-2.create"));

        $response->assertStatus(403);
    }

    public function test_can_view_prep_2_after_one_completed()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $user = $this->getParticipant($pair);
        Survey::factory()
            ->count(2)
            ->state(new Sequence(
                [
                    'type' => 'initial-2',
                    'category' => 'milestone'
                ],
                [
                    'type' => 'prep-1',
                    'category' => 'prep'
                ]
            ))
            ->completed()
            ->for($user)
            ->create();

        $video = WeeklySettings::findByNumber(2, true);
        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-2.create"));

        $response->assertViewIs('display-video');
        $response->assertSee($video->id);
        $response->assertSee($video->title);
    }

    public function test_can_view_partially_completed_survey()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $user = $this->getParticipant($pair);
        Survey::factory()
            ->count(4)
            ->state(new Sequence(
                [
                    'type' => 'initial-2',
                    'category' => 'milestone',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'week' => 1,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-2',
                    'category' => 'prep',
                    'week' => 2,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-3',
                    'category' => 'prep',
                    'week' => 3,
                    'data' => [
                        '_progress' => [ 0 => true]
                    ]
                ]
            ))
            ->for($user)
            ->create();

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-3.create"));
        $response->assertRedirect(route("survey.{$this->role}.prep-3.show", ['step' => 1]));
    }

    public function test_can_view_second_step()
    {
        $pair = Pair::factory()->create(['waitlist' => true, 'randomized_at' => now()->subMonths(8)]);
        $user = $this->getParticipant($pair);
        Survey::factory()
            ->count(4)
            ->state(new Sequence(
                [
                    'type' => 'initial-2',
                    'category' => 'milestone',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'week' => 1,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-2',
                    'category' => 'prep',
                    'week' => 2,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-3',
                    'category' => 'prep',
                    'week' => 3,
                    'data' => [
                        '_progress' => [ 0 => true]
                    ]
                ]
            ))
            ->for($user)
            ->create();

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-3.show", ['step' => 1]));
        $response->assertViewIs($this->getSecondStepView());
    }

    protected function getSecondStepView()
    {
        return "surveys.{$this->role}.weekly.feedback";
    }

    public function test_increment_participant_week_after_last_video()
    {

        $pair = Pair::factory()->randomized(false)->create();
        $user = $this->getParticipant($pair);
        Survey::factory()
            ->count(4)
            ->state(new Sequence(
                [
                    'type' => 'initial-2',
                    'category' => 'milestone',
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-1',
                    'category' => 'prep',
                    'week' => 1,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-2',
                    'category' => 'prep',
                    'week' => 2,
                    'completed_at' => now()
                ],
                [
                    'type' => 'prep-3',
                    'category' => 'prep',
                    'week' => 3,
                    'data' => [
                        '_progress' => [ 0 => true]
                    ]
                ]
            ))
            ->for($user)
            ->create();

        $response = $this->actingAs($user)->post(route("survey.{$this->role}.prep-3.update", ['step' => 1]));

        $user = $user->refresh();
        $this->assertEquals(1, $user->week);
    }

    public function test_waitlist_cannot_access_prep_videos()
    {
        $pair = Pair::factory()->randomized(true)->create();
        $user = $this->getParticipant($pair);

        $response = $this->actingAs($user)->get(route("survey.{$this->role}.prep-1.create"));

        $response->assertViewIs('error');
    }
}
