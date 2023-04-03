<?php

namespace Tests\Feature\ConsentSurveys;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Survey;
use App\Surveys\Patient\Steps\Consent\Quiz;
use Tests\TestCase;

class PatientConsentSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_survey()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::factory()->patient()->eligible()->create();

        $response = $this->actingAs($user)
            ->get(route('survey.patient.consent.create', ['slug' => 'initial']));

        $response->assertViewIs('surveys.patient.consent.page-one');
    }

    public function test_can_save_survey()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::factory()->patient()->eligible()->create();

        $response = $this->actingAs($user)
            ->post(route('survey.patient.consent.store', ['slug' => 'initial']));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $user->id,
            'type' => 'initial-consent'
        ]);
    }

    public function test_get_redirected_to_next_page()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::factory()->patient()->eligible()->create();

        $response = $this->actingAs($user)
            ->post(route('survey.patient.consent.store', ['slug' => 'initial']));

        $response->assertRedirect(route('survey.patient.consent.show', ['step' => 1, 'slug' => 'initial']));
    }

    public function test_can_see_later_steps()
    {
        $user = User::factory()->patient()->eligible()->create();

        $survey = Survey::factory()
            ->type('initial-consent')
            ->for($user)
            ->upToStep(0)
            ->create();

        $response = $this->actingAs($survey->user)
            ->get(route('survey.patient.consent.show', ['slug' => 'initial', 'step' => 1]));

        $response->assertViewIs('surveys.patient.consent.page-two');
    }

    public function test_incorrect_final_quiz_answer_redirected()
    {
        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->forPatient()
            ->upToStep(2)
            ->create();

        $input = array_combine(Quiz::fieldNames(), ['False', 'True', 'False', 'True', 'True', 'False', 'True', 'True', 'False', 'True']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.patient.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );

        $response->assertRedirect(route('survey.patient.consent-quiz.show', ['slug' => 'screening', 'step' => 3]));
        $response->assertSessionHasErrors(['survey']);
    }

    public function test_incomplete_final_quiz_answer_redirected()
    {

        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->forPatient()
            ->upToStep(2)
            ->create();

        //only need 8/10 correct so need to only give 7 answers
        $input = array_combine(array_slice(Quiz::fieldNames(), 0, 7), ['True', 'True', 'False', 'True', 'True', 'True', 'True']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.patient.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );


        $response->assertRedirect(route('survey.patient.consent-quiz.show', ['slug' => 'screening', 'step' => 3]));
        $response->assertSessionHasErrors(['survey']);

    }

    public function test_correct_final_quiz_forwarded_to_survey()
    {
        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->forPatient()
            ->upToStep(2)
            ->create();

        $input = array_combine(Quiz::fieldNames(), ['True', 'True', 'False', 'True', 'True', 'True', 'True', 'True', 'True', 'True']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.patient.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );

        $response->assertRedirect(route('survey.patient.screening.create'));
        $response->assertSessionHasNoErrors();
    }

    public function test_redirected_to_initial_consent_if_not_yet_done()
    {
        $patient = User::factory()
            ->eligible()
            ->patient()
            ->create();

        $response = $this->actingAs($patient)
            ->get(route('survey.patient.initial.create'));

        $response->assertRedirect(route('survey.patient.consent.create', ['slug' => 'initial']));
    }

    public function test_redirected_to_screening_consent_if_not_yet_done()
    {
        $patient = User::factory()
            ->patient()
            ->create();

        $response = $this->actingAs($patient)
            ->get(route('survey.patient.screening.create'));

        $response->assertRedirect(route('survey.patient.consent-quiz.create', ['slug' => 'screening']));
    }
}
