@php
    $none = $fields[0]['options'][4];
    $isNone =  "checkbox.includes('{$none}')";
    $onChange = "if ({$isNone}) { checkbox=['{$none}'] }";
@endphp

<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Program Development</h2>

    <x-input.survey-question :question="$fields[0]" x-model="checkbox" array>
        <div>
            <div>
                We are exploring the possibility of developing topic-specific online education programs. Which of the following programs would be helpful to your clients? (Please select all that apply.)
            </div>
            <div class="flex flex-col gap-y-1">
                @foreach ($fields[0]['options'] as $option)
                    @if($loop->last)
                        <x-input.checkbox-group.item
                            :question-name="$fields[0]['name']"
                            :answer-text="$option"
                            x-model="checkbox"
                            x-on:change="{{ $onChange }}"
                            />
                    @else
                    <x-input.checkbox-group.item
                        :question-name="$fields[0]['name']"
                        :answer-text="$option"
                        x-model="checkbox"
                        x-bind:disabled="{{ $isNone }}"
                        />
                    @endif
                @endforeach
            </div>

        </div>
    </x-input.survey-question>

    <x-input.textarea :question="$fields[1]">
        Are there any other education programs that you’d like us to create?
    </x-input.textarea>

    <x-input.radio-group :question="$fields[2]">
        Pace: If we offer one or more of these programs, at which pace should they be taught?
    </x-input.radio-group>


</x-layouts.survey>
