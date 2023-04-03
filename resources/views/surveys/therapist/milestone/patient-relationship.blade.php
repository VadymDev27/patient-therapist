@php
    $default = [];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Current Status of Working Relationship with Patient</x-input.heading>

    <x-input.yes-no.conditional :question="$fields[0]" condition="No">
        I am still treating this patient.

        <x-slot name="dependents">
        <x-input.text :question="$fields[1]">
            When did treatment end?
        </x-input.text>

        <x-input.survey-question :question="$fields[2]" x-model="checkbox" :default-data="$default">
            <div>
                <div class="">Please select the reason/s for discontinuation:</div>
                <div class="ml-2" >
                    <ul class="list-disc list-inside flex flex-col gap-y-1">
                        <li>Treatment was no longer feasible because
                            <div class="ml-8 flex flex-col gap-y-1">

                            @foreach(array_slice($fields[2]['options'],0,5) as $option)
                                <x-input.checkbox-group.item
                                    :question-name="$fields[2]['name']"
                                    :answer-text="$option"
                                    x-model="checkbox"
                                />
                            @endforeach
                            </div>
                        </li>

                        <li>The patient terminated treatment due to
                            <div class="ml-8 flex flex-col gap-y-1">
                                @foreach(array_slice($fields[2]['options'],5,6) as $option)
                                <x-input.checkbox-group.item
                                    :question-name="$fields[2]['name']"
                                    :answer-text="$option"
                                    x-model="checkbox"
                                />
                            @endforeach
                            <x-input.checkbox-group.item
                                    :question-name="$fields[2]['name']"
                                    :answer-text="$fields[2]['options'][11]"
                                    x-model="checkbox"
                                    with-conditional
                                >
                                <x-input.textarea.base :question-name="$fields[3]['name']">
                                    please specify:
                                </x-input.textarea.base>
                            </x-input.checkbox-group.item>
                            </div>
                        </li>

                        <li class="list-none pt-1">
                            <x-input.checkbox-group.item
                                :question-name="$fields[2]['name']"
                                :answer-text="$fields[2]['options'][12]"
                                x-model="checkbox"
                            />
                        </li>

                    </ul>
                </div>
            </div>
        </x-input.survey-question>

        <div>
            Please complete the following survey regarding the patient’s status at the time you last had contact with him/her/them.
        </div>
        </x-slot>
    </x-input.yes-no.conditional>
</x-layouts.survey>
