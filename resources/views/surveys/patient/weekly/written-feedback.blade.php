@php
    $prefix = 'WEF_';
    $options = ['I did them, they helped a lot.', 'I did them, they helped somewhat', 'I did them, but they didn\'t seem to help', 'I didn\'t do them.'];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Written Exercises Feedback</x-input.heading>

    <x-input.radio-group :question="$fields[0]">
        Select the response that best describes your experience with the last topic's written exercises.
    </x-input.radio-group>

    <x-input.textarea :question="$fields[1]">
        Optional: Other feedback about the written exercises?
    </x-input.textarea>

</x-layouts.survey>
