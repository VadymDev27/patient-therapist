@props(['question', 'options' => null])
@php
    $model = $attributes->get('x-model') ?? 'input';
    $options = $options ?? $question['options'];
@endphp

<x-input.survey-question :question="$question" {{ $attributes }}>
    <div class="w-full">
        @if($question['text'])
                {!! $question['text'] !!}
        @else
            {{ $slot }}
        @endif
        <div class="grid sm:justify-between sm:grid-flow-col auto-cols-fr grid-flow-row max-w-xl mt-2">
            @foreach($options as $val => $label)
            <div class="flex sm:flex-col items-start text-start sm:items-center sm:text-center gap-x-1 leading-snug break-words" x-id="['radio-item']">
                <input
                    class="block"
                    type="radio"
                    name="{{ $question['name'] }}"
                    :id="$id('radio-item')"
                    value="{{ str_replace('%','',$val) }}"
                    x-model="{{$model}}"
                    />
                <label
                    :for="$id('radio-item')"
                    class="contents"
                >
                    <div class="">{{ $val }}</div>
                    @if($label !== '')
                        <div class="sm:hidden">-</div>
                    @endif
                    <div class="m-0">{{ $label }}</div>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</x-input.survey-question>
