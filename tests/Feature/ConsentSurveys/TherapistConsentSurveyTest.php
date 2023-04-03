<?php

namespace Tests\Feature\ConsentSurveys;

use App\Models\Pair;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Survey;
use App\Surveys\Therapist\Steps\Consent\Quiz;
use Tests\TestCase;

class TherapistConsentSurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_survey()
    {
        $patient = User::factory()->eligible()->patient()->create();
        $therapist = $patient->getCoParticipant();
        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.consent.create', ['slug' => 'initial']));

        $response->assertViewIs('surveys.therapist.consent.page-one');
    }

    public function test_redirected_if_cant_see_survey_consent_is_for()
    {
        $pair = Pair::factory()->create();

        $therapist = $pair->therapist();
        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.consent.create', ['slug' => 'initial']));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_can_save_survey()
    {
        $patient = User::factory()->eligible()->patient()->create();
        $therapist = $patient->getCoParticipant();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.consent.store', ['slug' => 'initial']));

        $this->assertDatabaseHas('surveys', [
            'user_id' => $therapist->id,
            'type' => 'initial-consent'
        ]);
    }


    public function test_get_redirected_to_next_page()
    {
        $patient = User::factory()->eligible()->patient()->create();
        $therapist = $patient->getCoParticipant();

        $response = $this->actingAs($therapist)
            ->post(route('survey.therapist.consent.store', ['slug' => 'initial']));

        $response->assertRedirect(route('survey.therapist.consent.show', ['step' => 1, 'slug' => 'initial']));
    }

    public function test_can_see_later_steps()
    {
        $patient = User::factory()->eligible()->patient()->create();
        $therapist = $patient->getCoParticipant();
        $survey = Survey::factory()
            ->type('initial-consent')
            ->upToStep(0)
            ->for($therapist)
            ->create();

        $response = $this->actingAs($survey->user)
            ->get(route('survey.therapist.consent.show', ['slug' => 'initial', 'step' => 1]));

        $response->assertViewIs('surveys.therapist.consent.page-two');
    }

    public function test_incorrect_final_quiz_answer_redirected()
    {
        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->upToStep(2)
            ->create();

        $input = array_combine(Quiz::fieldNames(), ['False', 'True', 'False', 'True', 'True', 'False', 'True', 'True', 'False', 'True']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );

        $response->assertRedirect(route('survey.therapist.consent-quiz.show', ['slug' => 'screening', 'step' => 3]));
        $response->assertSessionHasErrors(['survey']);
    }

    public function test_incomplete_final_quiz_answer_redirected()
    {

        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->upToStep(2)
            ->create();

        //only need 8/10 correct so need to only give 7 answers
        $input = array_combine(array_slice(Quiz::fieldNames(),0,7), ['True', 'True', 'False', 'True', 'True', 'True', 'True']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );


        $response->assertRedirect(route('survey.therapist.consent-quiz.show', ['slug' => 'screening', 'step' => 3]));
        $response->assertSessionHasErrors(['survey']);

    }

    public function test_correct_final_quiz_forwarded_to_survey()
    {
        $survey = Survey::factory()
            ->type('screening-consent-quiz')
            ->upToStep(2)
            ->create();

        $input = array_combine(Quiz::fieldNames(), ['True', 'True', 'False', 'True', 'True', 'True', 'True', 'False', 'True', 'False']);
        $response = $this->actingAs($survey->user)
            ->post(
                route('survey.therapist.consent-quiz.update', ['slug' => 'screening', 'step' => 3]),
                $input
            );

        $response->assertRedirect(route('survey.therapist.screening.create'));
        $response->assertSessionHasNoErrors();
    }

    public function test_redirected_to_initial_consent_if_not_yet_done()
    {
        $patient = User::factory()
            ->eligible()
            ->patient()
            ->create();
        $therapist = $patient->getCoParticipant();

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.initial.create'));

        $response->assertRedirect(route('survey.therapist.consent.create', ['slug' => 'initial']));
    }

    public function test_redirected_to_screening_consent_if_not_yet_done()
    {
        $patient = User::factory()
            ->patient()
            ->create();
        $therapist = $patient->getCoParticipant();

        $response = $this->actingAs($therapist)
            ->get(route('survey.therapist.screening.create'));

        $response->assertRedirect(route('survey.therapist.consent-quiz.create', ['slug' => 'screening']));
    }
}
