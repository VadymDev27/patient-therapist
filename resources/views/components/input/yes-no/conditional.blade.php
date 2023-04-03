@props(['question', 'condition' => 'Yes'])
@php
    $model = $attributes->get('x-model') ?? 'conditional';
    $x_data = x_data_string($question['name'], $model, $attributes->has('x-model'));
    $show = "{$model}==='{$condition}'";
@endphp

<div x-data="{{ $x_data }}" class="contents" >
    <x-input.yes-no :question="$question" x-model="{{$model}}" has-outside-data {{ $attributes }}>
        {{ $slot }}
    </x-input.yes-no>
    <div x-cloak x-show="{{ $show }}" class="contents">
        {{ $dependents }}
    </div>
</div>
