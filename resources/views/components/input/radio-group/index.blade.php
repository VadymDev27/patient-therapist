@props(['question', 'item' => 'input.radio-group.item', 'defaultData' => ''])
@php
    $model = $attributes->get('x-model') ?? 'radio';
@endphp

<x-input.radio-group.base
    :question="$question"
    :default-data="$defaultData"
    {{ $attributes }}
>
    {{ $slot }}
    <x-slot name="options">
        @foreach($question['options'] as $option)
        <x-dynamic-component
            :component="$item"
            :question-name="$question['name']"
            :answer-text="$option"
            :x-model="$model"
            text-input="{{
                $question['withOther'] && $option==='Other'
                ? $question['name'] . '_Other'
                : ''
            }}"
        />
        @endforeach
    </x-slot>
</x-input.radio-group.base>

