@props(['question'])

<x-input.survey-question :question="$question" {{ $attributes}} >
    <div class="flex flex-grow flex-col sm:flex-row justify-between">
        <div class="align-middle">
            <x-input.survey-question.slot :question="$question">
                {{ $slot }}
            </x-input.survey-question.slot>
        </div>
        <div class="flex">
        @foreach ($question['options'] as $i)
            <div class="flex flex-col ml-2 mr-2 items-center" x-id="['radio']">
                <input type="radio"
                    name="{{ $question['name'] }}"
                    :id="$id('radio')"
                    value="{{ $i }}"
                    x-model="input"/>
                <label :for="$id('radio')">{{ $i }}</label>
            </div>
        @endforeach
        </div>
    </div>
</x-input.survey-question>
