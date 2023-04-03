<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Patient Treatment History</h2>

    @php
        $dxModels = ['dxYears', 'dxMonths'];
    @endphp

    <x-input.duration :year-question="$fields[0]" :month-question="$fields[1]">
        How long has the patient been formally diagnosed with a dissociative disorder or the dissociative subtype of PTSD?
    </x-input.duration>

    <x-input.duration :year-question="$fields[2]" :month-question="$fields[3]">
        How long has the patient been in treatment with you?
    </x-input.duration>

    <x-input.radio-group :question="$fields[4]">
        In what setting have you primarily seen this patient? (check primary setting)"
    </x-input.radio-group>

    <x-input.yes-no.conditional :question="$fields[5]">
        Has the patient ever attempted suicide?

        <x-slot name="dependents">
            <x-input.number :question="$fields[6]">
                Estimated number of suicide attempts in lifetime:
            </x-input.number>

            <x-input.number :question="$fields[7]">
                Estimated number of suicide attempts in the last six months:
            </x-input.number>
        </x-slot>
    </x-input.yes-no.conditional>

    <x-input.yes-no.conditional :question="$fields[8]">
        Has the patient ever intentionally harmed themselves (excluding suicide attempts)?

        <x-slot name="dependents">
            <x-input.radio-group.horizontal :question="$fields[9]">
                Describe the extent of the most severe self-harm injuries:
            </x-input.radio-group.horizontal>

            <x-input.number :question="$fields[10]">
                Estimated number of times patient has self-harmed in the last six months:
            </x-input.number>
        </x-slot>
    </x-input.yes-no.conditional>

    <x-input.yes-no.conditional :question="$fields[11]">
        Has the patient had psychiatric hospitalizations?

        <x-slot name="dependents">
            <x-input.number :question="$fields[12]">
                Estimated number of lifetime psychiatric hospitalizations:
            </x-input.number>

            <x-input.number :question="$fields[13]">
                Number of days inpatient in a psychiatric hospital in the last 6 months:
            </x-input.number>

            <x-input.number :question="$fields[14]">
                Number of days in a daytime-only hospital program in the last 6 months:
            </x-input.number>
        </x-slot>
    </x-input.yes-no.conditional>
</x-layouts.survey>
