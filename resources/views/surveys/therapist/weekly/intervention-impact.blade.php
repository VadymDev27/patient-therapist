<x-layouts.survey :step="$step" >
    <h2 class="text-xl font-semibold">Intervention Impact Feedback</h2>
    <div x-data="{{ x_data_string($fields[0]['name'], 'impact', false) }}">
        <x-input.radio-group :question="$fields[0]" x-model="impact" has-outside-data>
            How would you currently assess the TOP DD Network study's impact on your client?
        </x-input.radio-group>

        <div x-show="impact==='{{ $fields[0]['options'][0] }}'" class="flex flex-col gap-y-2">
            <div>You have indicated that your patient is being strongly negatively impacted by participating in this study.  Although we have been careful about the language we use throughout, some patients may still become unexpectedly triggered.</div>
            <div>Please use your clinical judgment to determine if this is a brief, manageable reaction, and continuing in the intervention is important enough to the patient’s overall recovery to continue with the study, or if you think it is in your patient’s best interest to have the two of you discontinue the study.</div>
            <div>Should you determine that discontinuing involvement in the study is in the best interest of your patient, please complete the following survey to help us learn how to refine and improve our work.</div>
            <div>
                @php $fields=array_slice($fields,1) @endphp
                <x-input.heading>Therapist Discontinuation Survey</x-input.heading>
                @include('surveys.therapist.discontinuation.questions')
            </div>
        </div>
    </div>

</x-layouts.survey>
