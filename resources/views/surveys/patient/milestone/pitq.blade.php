<x-layouts.survey :step="$step">
    <x-input.heading>Progress in Treatment Questionnaire</x-input.heading>
    <div>
        Please select the number that reflects what percentage of time each of the following statements has been true of you <u><i>in the last week</i></u>.
    </div>

    @for($i = 0; $i < 26; $i++)
        <x-input.radio-group.horizontal :question="$fields[$i]" />
    @endfor

    <x-input.yes-no.conditional :question="$fields[26]">
        <x-slot name="dependents">
            @for($i = 27; $i < 32; $i++)
                <x-input.radio-group.horizontal :question="$fields[$i]" />
            @endfor
        </x-slot>
    </x-input.yes-no.conditional>

</x-layouts.survey>
