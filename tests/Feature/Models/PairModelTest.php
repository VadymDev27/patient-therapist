<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;

use App\Models\Pair;
use App\Models\User;
use Tests\TestCase;

class PairModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_co_participant()
    {
        $pair = Pair::factory()->create();

        $this->assertTrue($pair->therapist()->is($pair->patient()->getCoParticipant()));
    }

    public function test_pair_create_from_users_links_users()
    {
        $patient = User::factory()->patient()->create();
        $therapist = User::factory()->create();

        Pair::createFromUsers($patient, $therapist);
        $this->assertTrue($patient->refresh()->getCoParticipant()->id === $therapist->id);
    }

    public function test_pair_can_discontinue()
    {
        $match = Pair::factory()->create();
        $pair = Pair::factory()->create(['match_id' => $match->id]);
        $match->match_id = $pair->id;
        $match->save();

        $this->assertTrue($pair->match()->is($match));

        $pair->discontinue();
        $this->assertNull($match->refresh()->match_id);
        $this->assertNull($pair->match_id);
        $this->assertTrue($pair->discontinued);
    }

    public function test_pair_without_match_can_discontinue()
    {
        $pair = Pair::factory()->create();
        $pair->discontinue();

        $this->assertTrue($pair->discontinued);

    }

    public function test_normal_pair_is_not_test()
    {
        $pair = Pair::factory()->create();
        $this->assertFalse($pair->isTest());
    }
}
