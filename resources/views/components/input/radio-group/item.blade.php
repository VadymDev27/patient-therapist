@props([
    'questionName',
    'answerText',
    'xModel',
    'xShow'=> null,
    'textInput' => '',
]
)

@php
    $x_show = $xShow ?? "{$xModel}=='{$answerText}'";
@endphp
<div {{ $attributes->whereStartsWith('x-show')->class(['py-0.5']) }} >
    <div class="flex gap-x-2 items-start" x-id="['radio-item']">
        <input {{ $attributes->whereDoesntStartWith('x-show')->merge([
            'type' => 'radio',
            'value' => $answerText,
            'name' => $questionName,
            'x-model' => $xModel
        ]) }}
            :id="$id('radio-item')"/>
        <label
            :for="$id('radio-item')"
            class="leading-tight"
        >
            {{ $answerText }}
        </label>
        {{ $inline ?? '' }}
    </div>
    @if($textInput)
    <div class="ml-6 flex gap-x-4" x-show="{{$x_show}}" x-id="['text-input']">
        <label :for="$id('text-input')" class="m-0">Please specify: </label>
        <input
            type="text"
            class="border-0 border-b border-grey-dark p-0 m-0"
            name="{{ $textInput }}"
            value="{{ old($textInput)}}"
            :id="$id('text-input')"
        />
    </div>
    @endif
    @if($attributes->has('with-conditional'))
    <div x-show="{{$x_show}}">
        {{ $slot }}
    </div>
    @endif
</div>
