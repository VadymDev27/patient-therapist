@props(['question', 'defaultData' => '', 'defaultModel' => 'input', 'array' => false])
@php
    $model = $attributes->get('x-model') ?? $defaultModel;
    $x_data = x_data_string($question['name'], $model, $attributes->has('has-outside-data'), ($array ? [] : $defaultData));
@endphp

<div {{ $attributes->whereDoesntStartWith('x-model')->merge(['class' => 'm-0 py-2 flex flex-row', 'x-data' => $x_data]) }}
    @if($question['required'])
    :class="{{"(highlightIncomplete && {$model}.length === 0) && 'bg-blue-200'"}}"
    @elseif($question['isMain'])
    :class="{{"(highlightMain && {$model}.length === 0) && 'bg-blue-200'"}}"
    @endif
>
    @if(! $attributes->has('no-number'))
    <div class="pr-2 text-left">
        {{ $question['number'] . '.' }}
    </div>
    @endif
    {{ $slot }}
</div>
