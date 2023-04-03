<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Pair;
use App\Models\User;
use App\Models\ScreeningSurvey;
use App\Models\Survey;
use App\Surveys\Trait\UsesFinalWeek;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase, UsesFinalWeek;

    public function test_users_without_screening_survey_shown_screening()
    {
        /** @var \App\Models\User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertSee($user->getSurveyUrl('screening'));
    }
}
