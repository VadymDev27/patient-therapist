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

class PatientPrepVideosTest extends TherapistPrepVideosTest
{
    protected string $role = 'patient';

    protected function getSecondStepView()
    {
        return "surveys.{$this->role}.weekly.video-feedback";
    }
}
