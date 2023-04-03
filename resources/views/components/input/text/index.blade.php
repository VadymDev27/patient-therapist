@props(['question', 'type' => 'input'])
@php
    $model = $attributes->get('x-model') ?? 'input';
    $data = json_encode(old($question['name']) ?? '' );
    $x_data = $attributes->has('x-model')
                ? '{}'
                : "{ {$model}: $data }";
@endphp

<x-input.survey-question :question="$question" {{ $attributes }} x-data="{{$x_data}}">
    <x-input.text.base :question-name="$question['name']" x-model="{{ $model }}" :type="$type">
        {{ $slot }}
    </x-input.text.base>
</x-input.survey-question>
