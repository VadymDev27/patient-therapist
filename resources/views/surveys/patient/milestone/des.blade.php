<x-layouts.survey :step="$step" required-message="We use the results of this questionnaire to randomize participants.  Please note that if you opt to not complete all 28 questions, we won’t be able to randomize you and your therapist into the program.">
    <x-input.heading>Dissociative Experiences Scale</x-input.heading>
    <div class="mb-4 whitespace-pre-line">
        DIRECTIONS: This questionnaire consists of twenty-eight questions about experiences that you may have in your daily life.  We are interested in how often you have had these experiences <u><b>in the last month</b></u>.  It is important, however, that your answers show how often these experiences happen to you when you <u>are not</u> under the influence of alcohol or drugs.

        To answer the questions, please determine to what degree the experience described in the question has applied to you <i>in this last month</i> and select the number to show what percentage of the time you have had that experience in the last month.
    </div>

    @php
        $options=[
            '0%' => 'never',
            10 => '',
            20 => '',
            30 => '',
            40 => '',
            50 => '',
            60 => '',
            70 => '',
            80 => '',
            90 => '',
            '100%' => 'always'
        ];
    @endphp

    @foreach ($fields as $question)
        <x-input.radio-group.horizontal :question="$question" :options="$options"/>
    @endforeach

</x-layouts.survey>
