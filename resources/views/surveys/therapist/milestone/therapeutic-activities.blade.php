<x-layouts.survey :step="$step">
    <x-input.heading>Therapeutic Activities</x-input.heading>

    <p class="mt-4">Please circle the number indicating the frequency with which you engaged in the following therapeutic tasks with this patient during sessions over <span class="font-bold underline">the last 6 months.</span>  Then circle the number indicating how successful those interventions were <span class="font-bold underline">in the last 6 months</span>.  Note that many of these tasks overlap with one another.</p>

    @for($i = 0; $i < 27; $i++)
        <x-input.radio-group.horizontal :question="$fields[$i]" />
    @endfor

    <div class="" x-data="{{ x_data_string($fields[27]['name'], 'radio', false) }}">
        <x-input.radio-group.horizontal :question="$fields[27]" x-model="radio" has-outside-data />
        <x-input.text :question="$fields[28]" x-show="['1','2','3','4'].includes(radio)" x-cloak>
            Which approach/es are you using with this patient?
        </x-input.text>
    </div>
</x-layouts.survey>
