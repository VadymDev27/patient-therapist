<?php

namespace Tests\Feature\Screening;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Survey;
use App\Notifications\PatientInvitation;
use App\Surveys\Therapist\ScreeningSurvey;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ComputeTherapistEligibilityTest extends TestCase
{
    use RefreshDatabase;

    private string $action = \App\Surveys\Action\ComputeTherapistEligibility::class;

    private function doActionOnSurvey(array $data): Survey
    {
        $survey = Survey::factory()
            ->type('screening')
            ->create(
                [
                    'data' => array_merge(\App\Surveys\Therapist\ScreeningSurvey::generateFakeData(), $data),
                    'category' => 'screening'
                ]
            );

        app()->make($this->action)->execute($survey);

        return $survey->refresh();
    }

    private function assertEligible($data)
    {
        $survey = $this->doActionOnSurvey($data);

        $this->assertTrue($survey->user->is_eligible);
    }

    public function test_all_ptsd_true_marked_eligible()
    {
        $data = array_fill_keys(appendStringToEach(range(1, 7), 'TSS_PTSD_'), 'Yes') + ['TSS_PtHx_2_Years' => 4];
        $this->assertEligible($data);
    }

    public function test_all_did_true_marked_eligible()
    {
        $data = array_fill_keys(appendStringToEach(range(1, 5), 'TSS_DID_'), 'Yes') + ['TSS_PtHx_2_Years' => 4];
        $this->assertEligible($data);
    }

    public function test_all_cptsd_true_marked_eligible()
    {
        $data = array_fill_keys(appendStringToEach(range(1, 3), 'TSS_CPTSD_'), 'Yes') + ['TSS_PtHx_2_Years' => 4];
        $this->assertEligible($data);
    }

    public function test_any_osdd_true_marked_eligible()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 6];
        $this->assertEligible($data);
    }

    public function test_ineligible_marked_ineligible()
    {
        $data = array_fill_keys(appendStringToEach(range(1, 4), 'TSS_DID_'), 'Yes') + ['TSS_DID_5' => 'No', 'TSS_PtHx_2_Years' => 4];

        $survey = $this->doActionOnSurvey($data);

        $this->assertFalse($survey->user->is_eligible);
    }

    public function test_diagnosis_fail_reason_correctly_set()
    {
        $data = array_fill_keys(appendStringToEach(range(1, 4), 'TSS_DID_'), 'Yes') + ['TSS_DID_5' => 'No', 'TSS_PtHx_2_Years' => 4];

        $survey = $this->doActionOnSurvey($data);

        $this->assertEquals('diagnosis', $survey->data['fail_reason']);
    }

    public function test_marked_ineligible_if_not_enough_tx_time()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 2, 'TSS_PtHx_2_Years' => 0];

        $survey = $this->doActionOnSurvey($data);

        $this->assertFalse($survey->user->is_eligible);
    }

    public function test_tx_time_reason_correctly_set()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 2, 'TSS_PtHx_2_Years' => 0];


        $survey = $this->doActionOnSurvey($data);

        $this->assertEquals('treatment-duration', $survey->data['fail_reason']);
    }

    public function test_can_access_fail_reason()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 2, 'TSS_PtHx_2_Years' => 0];


        $survey = $this->doActionOnSurvey($data);

        $this->assertEquals('treatment-duration', $survey->user->screenFailReason());
    }

    public function test_ineligible_survey_properly_reset()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 2, 'TSS_PtHx_2_Years' => 0];

        $survey = $this->doActionOnSurvey($data);

        $response = $this->actingAs($survey->user)->post(route('screening-survey.reset'));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $survey->user->id,
            'type' => 'ineligible',
            'category' => 'screening',
            'data' => json_encode($survey->data)
        ]);
    }

    public function test_ineligible_survey_properly_copied()
    {
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 2, 'TSS_PtHx_2_Years' => 0];

        $survey = $this->doActionOnSurvey($data);

        $response = $this->actingAs($survey->user)->post(route('screening-survey.reset'));

        $newSurvey = $survey->user->getSurvey('screening');

        // can't check all because the multiselect options won't match
        $fieldsToCheck = ['TSS_Demographics_1', 'TSS_Demographics_7', 'TSS_Demographics_14'];

        $match = collect($fieldsToCheck)->every(function ($field) use ($newSurvey, $survey) {
            return $survey->data($field) === $newSurvey->data($field);
        });

        $nulls = collect(ScreeningSurvey::fieldNames(1))->every(function ($field) use ($newSurvey, $survey) {
            return is_null($newSurvey->data($field));
        });

        $this->assertTrue($nulls);
        $this->assertTrue($match);
        $this->assertEquals([0 => true], $newSurvey->data("_progress"));
        $this->assertDatabaseHas(
            'surveys',
            [
                'user_id' => $survey->user->id,
                'type' => 'ineligible'
            ]
        );
    }

    public function test_patient_invitation_sent_for_eligible_survey()
    {
        Notification::fake();
        $data = ['TSS_OSDD_2' => 'Yes', 'TSS_PtHx_2_Months' => 6];

        $survey = $this->doActionOnSurvey($data);

        Notification::assertSentTo($survey->user, PatientInvitation::class);
    }

    public function test_no_patient_invitiation_sent_for_ineligible_survey()
    {
        Notification::fake();

        $data = array_fill_keys(appendStringToEach(range(1, 4), 'TSS_DID_'), 'Yes') + ['TSS_DID_5' => 'No', 'TSS_PtHx_2_Years' => 4];

        $survey = $this->doActionOnSurvey($data);


        Notification::assertNotSentTo($survey->user, PatientInvitation::class);
    }
}
