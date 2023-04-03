<x-layouts.survey :step=$step>
    <x-input.heading>Patient Discontinuation Survey</x-input.heading>

    <div>Participants in the study are able to discontinue participation at any time.  If either therapist or patient decides to withdraw from the study, the other will also need to withdraw from the study.  If patients discontinue treatment with the therapist they enrolled in the TOP DD RCT Network study with, neither they or their therapist will be able to continue in the study, even with a different therapist or patient.  Participants are not able to re-enroll at a later time.</div>
    <div class="mt-2">It would be very helpful to understand why you would like to discontinue being in this study.  Please select all the options that are relevant to your decision to discontinue being in the study.</div>

    <x-input.radio-group.base :question="$fields[0]" array x-model="responses" no-number>
        <x-slot name="options">
            @foreach ($fields[0]['options'] as $option)
                <x-input.checkbox-group.item
                    :question-name="$fields[0]['name']" :answer-text="$option"
                    x-model="responses"/>
            @endforeach
            <x-input.textarea.base :question-name="$fields[1]['name']" class="ml-8" x-show="responses.includes('Other')">
                Please specify:
            </x-input.textarea.base>
        </x-slot>
    </x-input.radio-group.base>
</x-layouts.survey>
