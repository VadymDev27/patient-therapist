@php
    $prefix = 'PEF_';
    $options = ['I did them, they helped a lot.', 'I did them, they helped somewhat', 'I did them, but they didn\'t seem to help', 'I didn\'t do them.'];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Practice Exercises Feedback</x-input.heading>

    <x-input.radio-group :question="$fields[0]">
        Select the response that best describes your experience with the last topic's practice exercises.
    </x-input.radio-group>

    <x-input.textarea :question="$fields[1]">
        Optional: Other feedback about the practice exercises?
    </x-input.textarea>

</x-layouts.survey>
