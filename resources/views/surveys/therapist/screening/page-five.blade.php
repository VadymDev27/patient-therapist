<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Developmental History</h2>

    <x-input.radio-group.horizontal :question="$fields[0]">
        Socioeconomic status of family of origin (select number):
    </x-input.radio-group.horizontal>

    <x-input.radio-group :question="$fields[1]">
        Was the patient emotionally or psychologically abused as a child?
    </x-input.radio-group>

    <x-input.radio-group :question="$fields[2]">
        Did the patient witness domestic violence as a child?
    </x-input.radio-group>

    <x-input.radio-group :question="$fields[3]">
        Was the patient physically abused as a child?
    </x-input.radio-group>

    <x-input.radio-group :question="$fields[4]">
        Was the patient sexually abused as a child?
    </x-input.radio-group>

    <x-input.radio-group :question="$fields[5]">
        Was the patient neglected as a child?
    </x-input.radio-group>

    <h2 class="text-xl font-semibold">Adult Trauma History</h2>

    <x-input.radio-group :question="$fields[6]">
        Has the patient reported having been raped or the victim of a sexual assault in adulthood?
    </x-input.radio-group>

    <x-input.yes-no.conditional :question="$fields[7]">
        Has the patient reported having been in a sexually abusive relationship as an adult?
        <x-slot name="dependents">
            <x-input.radio-group :question="$fields[8]">
                Has the patient reported being the victim, perpetrator, or both?
            </x-input.radio-group>
        </x-slot>
    </x-input.yes-no.conditional>

    <x-input.yes-no.conditional :question="$fields[9]">
        Has the patient reported having been in a physically abusive relationship as an adult?
        <x-slot name="dependents">
            <x-input.radio-group :question="$fields[10]">
                Has the patient reported being the victim, perpetrator, or both?
            </x-input.radio-group>
        </x-slot>
    </x-input.yes-no.conditional>

    <x-input.yes-no.conditional :question="$fields[11]">
        Has the patient reported having been in an emotionally and/or verbally abusive relationship as an adult?
        <x-slot name="dependents">
            <x-input.radio-group :question="$fields[12]">
                Has the patient reported being the victim, perpetrator, or both?
            </x-input.radio-group>
        </x-slot>
    </x-input.yes-no.conditional>

</x-layouts.survey>
