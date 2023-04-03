<?php

namespace App\Surveys\Action;

use Surveys\Action\SurveyAction;
use App\Models\Survey;
use App\Notifications\PatientInvitation;
use App\Notifications\PatientNotEligibleEmail;
use App\Notifications\WelcomeEmail;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class ComputeTherapistEligibility extends SurveyAction
{
    public function execute(Survey $survey)
    {
        $therapist = $survey->user;

        $reason = $this->computeScreenFailReason($survey->data);
        $therapist->setScreenResult(is_null($reason), $reason);

        if ($therapist->is_eligible) {
            $therapist->notify(new PatientInvitation);
        }
    }

    public function computeEligibility(array $data): bool
    {
        return $this->checkDiagnosisConditions($data) && $this->checkTreatmentDuration($data);
    }

    // using strings here is not ideal but fastest way to do this until upgrade to PHP 8.1 for native enum
    private function computeScreenFailReason(array $data): string | null
    {
        if (! $this->checkDiagnosisConditions($data)) {
            return 'diagnosis';
        }
        if (! $this->checkTreatmentDuration($data)) {
            return 'treatment-duration';
        }
        return null;
    }

    private function checkTreatmentDuration(array $data): bool
    {
        return CarbonInterval::years(data_get($data, 'TSS_PtHx_2_Years'))->months(data_get($data, 'TSS_PtHx_2_Months'))->greaterThanOrEqualTo(CarbonInterval::months(3));
    }

    private function checkDiagnosisConditions(array $data): bool
    {
        return collect($this->diagnosisConditions())->contains(function ($value) use ($data) {
            return $this->isConditionTrue($value, $data);
        });
    }

    private function diagnosisConditions(): array
    {
        return [
            array_fill_keys(appendStringToEach(range(1,7),'TSS_PTSD_'), 'Yes'),
            array_fill_keys(appendStringToEach(range(1,5),'TSS_DID_'), 'Yes'),
            array_fill_keys(appendStringToEach(range(1,3),'TSS_CPTSD_'), 'Yes'),
            ['TSS_OSDD_1' => 'Yes'],
            ['TSS_OSDD_2' => 'Yes'],
            ['TSS_OSDD_3' => 'Yes'],
            ['TSS_OSDD_4' => 'Yes']
        ];
    }

    private function isConditionTrue(array $condition, array $data): bool
    {
        return collect($condition)->every(function ($value, $key) use ($data) {
            return data_get($data,$key) === $value;
        });
    }
}
