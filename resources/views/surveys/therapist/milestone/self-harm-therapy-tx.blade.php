<x-layouts.survey :step="$step">
    <x-input.heading>Recent Suicide, Self-Harm, Hospitalization</x-input.heading>
    @php
    $questions=[
        'Estimated number of suicide attempts',
        'Estimated number of times the patient has self-harmed',
        'Number of days inpatient in a psychiatric hospital',
        'Number of days in a daytime-only hospital program'
    ];
    $timeframe='in the last 6 months.';
    @endphp

    @foreach ($questions as $question)
        <x-input.number :question="$fields[$loop->index]">
            {{ $question . ' '}}<span class="font-bold underline">{{ $timeframe }}</span>
        </x-input.text-input>
    @endforeach

    @php
    $prefix= "TherapyFocus_";
    $options=[
        1 => '(Stabilization & establishing safety)',
        2 => '',
        3 => '(Processing trauma\'s impact)',
        4 => '',
        5 => '(Reconnection with self and others)'
    ];
    @endphp
    <x-input.heading>Individual Psychotherapy Focus</x-input.heading>

    <x-input.radio-group.horizontal :question="$fields[4]">
        What stage of treatment most characterizes your work with this patient <span class='font-bold underline'>during the last 6 months</span>?
    </x-input.radio-group.horizontal>

    <x-input.heading>Other treatments</x-input.heading>

    <x-input.survey-question :question="$fields[5]" x-model="checkbox" array>
        <div>
            <div>Please indicate any additional treatments this client has received <span class="font-bold underline">in the last 6 months</span>.
            </div>

            @foreach ($fields[5]['options'] as $option)
                <x-input.checkbox-group.item
                    :question-name="$fields[5]['name']"
                    :answer-text="$option"
                    x-model="checkbox"
                    />
            @endforeach

            <div x-cloak x-show="checkbox.includes('Other treatments')" class="ml-4">
                <x-input.textarea.base :question-name="$fields[6]['name']">
                    Please describe:
                </x-input.textarea.base>
            </div>
        </div>
    </x-input.survey-question>

</x-layouts.survey>
