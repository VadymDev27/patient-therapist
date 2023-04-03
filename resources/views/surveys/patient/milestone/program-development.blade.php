<x-layouts.survey :step="$step">
    <x-input.heading>Program Development</x-input.heading>

    <x-input.textarea :question="$fields[0]" />

    <x-input.radio-group :question="$fields[1]" />
</x-layouts.survey>
