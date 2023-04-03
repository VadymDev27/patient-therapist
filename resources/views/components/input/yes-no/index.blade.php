@props(['question', 'withUnclear' => false, 'left' => 'Yes', 'right' => 'No'])
@php
    $model = $attributes->get('x-model') ?? 'input';
@endphp

<x-input.survey-question :question="$question" :model="$model" {{ $attributes }} >
    <div>
        <x-input.survey-question.slot :question="$question">
            {{ $slot }}
        </x-input.survey-question.slot>
        <div x-id="['yes','no', 'unclear']">
            <input
                type="radio"
                x-model="{{ $model }}"
                value="{{ $left }}"
                name="{{ $question['name'] }}"
                :id="$id('yes')"
            />
            <label class="inline-flex items-center" :for="$id('yes')">
                {{ $left }}
            </label>
            <input
                type="radio"
                x-model="{{ $model }}"
                value="{{ $right }}"
                name="{{ $question['name'] }}"
                :id="$id('no')"
                />
            <label class="inline-flex items-center" :for="$id('no')">
                {{ $right }}
            </label>
            @if($withUnclear)
                <input
                    type="radio"
                    x-model="{{ $model }}"
                    value="Unclear"
                    name="{{ $question['name'] }}"
                    :id="$id('unclear')"
                    />
                <label class="inline-flex items-center" :for="$id('unclear')">
                    Unclear
                </label>
            @endif
        </div>
    </div>
</x-input.survey-question>
