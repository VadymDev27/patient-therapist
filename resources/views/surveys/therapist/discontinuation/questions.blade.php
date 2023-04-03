<div>
    Participants in the study are able to discontinue participation at any time.  If either therapist or patient decides to withdraw from the study, the other will also need to withdraw from the study.  If patients discontinue treatment with the therapist they enrolled in the TOP DD RCT Network study with, neither they or their therapist will be able to continue in the study, even with a different therapist or patient.  Participants are not able to re-enroll at a later time.
</div>
<div class="mt-2">
    It would be very helpful to understand why you would like to discontinue being in this study.  Please select all the options that are relevant to your decision to discontinue being in the study.
</div>
<x-input.radio-group.base :question="$fields[0]" array x-model="TDS" no-number>
    It would be very helpful to understand why you would like to discontinue being in this study.  Please select all the options that are relevant to your decision to discontinue being in the study.

    <x-slot name="options" class="ml-4">
    @for($i = 0; $i < 21; $i++)
        <x-input.checkbox-group.item
            :question-name="$fields[0]['name']"
            :answer-text="$fields[0]['options'][$i]"
            x-model="TDS"
        />
    @endfor

    <x-input.checkbox-group.item
            :question-name="$fields[0]['name']"
            :answer-text="$fields[0]['options'][21]"
            x-model="TDS"
            with-conditional
        >
        <x-input.textarea.base :question-name="$fields[1]['name']" class="ml-8">
                We would be very grateful if you would indicate which study material/s you believe your patient had a negative reaction to, and explain a bit about their reaction so we can learn whether the intervention needs to be adapted.  Thank you very much!
        </x-input.textarea.base>
    </x-input.checkbox-group.item>

    <x-input.checkbox-group.item
            :question-name="$fields[0]['name']"
            :answer-text="$fields[0]['options'][22]"
            x-model="TDS"
            with-conditional
        >
        <x-input.textarea.base :question-name="$fields[2]['name']" class="ml-8">
                Please specify:
        </x-input.textarea.base>
    </x-input.checkbox-group.item>
    </x-slot>
</x-input.radio-group.base>
