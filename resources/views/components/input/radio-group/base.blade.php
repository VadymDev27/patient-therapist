@props(['question', 'defaultData' => ''])

<x-input.survey-question
    :question="$question" {{ $attributes }} :default-data="$defaultData" default-model="radio">
    <div>
        <x-input.survey-question.slot :question="$question">
            {{ $slot }}
        </x-input.survey-question.slot>
        <div {{ $options->attributes->class(['flex', 'flex-col']) }}>
            {{ $options }}
        </div>
    </div>
</x-input.survey-question>
