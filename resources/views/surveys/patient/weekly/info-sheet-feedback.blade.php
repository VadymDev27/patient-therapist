@php
    $prefix = 'ISF_';
    $options = ['True', 'More true than not', 'Neutral', 'More false than not', 'False'];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Information Sheet Feedback</x-input.heading>

    <x-input.radio-group :question="$fields[0]">
        The information sheet included helpful information.
    </x-input.radio-group>

    <x-input.radio-group :question="$fields[1]">
        The information sheet was easy to follow and understand.
    </x-input.radio-group>

    <x-input.textarea :question="$fields[2]">
        Optional: Other feedback about the information sheet?
    </x-input.textarea>

</x-layouts.survey>
