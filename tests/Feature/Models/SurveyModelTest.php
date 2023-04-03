<?php

namespace Tests\Feature\Models;

use App\Models\Survey;
use App\Models\User;
use App\Surveys\Therapist\Weekly\WeeklySurvey;

use Tests\TestCase;

class SurveyModelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_completed_works_properly()
    {
        $survey = Survey::make([
            'data' => [],
            'type' => 'bogus-type',
        ]);

        $survey->completed_at = now();
        $this->assertTrue($survey->isComplete());
    }

    public function test_weekly_fieldnames_dont_include_discontinuation()
    {
        $names = WeeklySurvey::fieldNames();

        $this->assertTrue(in_array('TIIF_1', $names));
        $this->assertFalse(in_array('TDS', $names));
    }

}
