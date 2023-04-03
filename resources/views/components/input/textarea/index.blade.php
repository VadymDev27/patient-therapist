@props(['question', 'rows' => '3'])
@php
    $model = $attributes->get('x-model') ?? 'input';
@endphp

<x-input.survey-question :question="$question" :model="$model" {{ $attributes }}>
    <x-input.textarea.base :question-name="$question['name']" :x-model="$model" :rows="$rows" has-outside-data>
        <x-input.survey-question.slot :question="$question">
            {{ $slot }}
        </x-input.survey-question.slot>
    </x-input.textarea.base>
</x-input.survey-question>

