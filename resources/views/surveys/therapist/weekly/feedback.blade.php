<x-layouts.survey :step="$step">
    <x-input.heading>{{ $step['title'] }}</x-input.heading>

    <x-input.radio-group :question="$fields[0]" />

    <x-input.radio-group :question="$fields[1]" />

    <x-input.textarea :question="$fields[2]" />
</x-layouts.survey>
