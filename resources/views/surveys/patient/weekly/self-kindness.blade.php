@php
    $prefix = 'SK_';
    $options = [
        1 => '(almost never)',
        2 => '',
        3 => '',
        4 => '',
        5 => '(almost always)'
    ];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Self Kindness Survey</x-input.heading>
    <div>
        Please read each statement carefully before answering. To the left of each item, indicate how often you behave in the stated manner in the <u><b>last seven days</b></u>:
    </div>

    <x-input.radio-group.horizontal :question="$fields[0]">
        When I’m going through a very hard time, I give myself the caring and tenderness I need.
    </x-input.radio-group.horizontal>

    <x-input.radio-group.horizontal :question="$fields[1]">
        I try to be understanding and patient towards those aspects of my personality I don’t like.
    </x-input.radio-group.horizontal>
</x-layouts.survey>
