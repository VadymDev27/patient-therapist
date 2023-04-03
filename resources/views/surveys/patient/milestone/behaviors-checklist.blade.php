<x-layouts.survey :step="$step">
    <x-input.heading>Behaviors Checklist</x-input.heading>
    <div class="flex flex-col">
        <div>Please list the number of times you have done the following behaviors <span class="underline font-bold">in the last 30 days.</span> </div>
    </div>

    @for($i = 0; $i < 3; $i++)
    <x-input.number :question="$fields[$i]">
        {!! $fields[$i]['text'] !!}
    </x-input.number>
    @endfor

    <div class="" x-data="{{ x_data_string($fields[3]['name'], 'alcohol', false) }}">
        <x-input.number :question="$fields[3]" x-model="alcohol">
            {!! $fields[3]['text'] !!}
        </x-input.number>
        <x-input.yes-no x-show="alcohol > 0" :question="$fields[4]">
            On average, when you drank alcohol in the <b>PAST 30 DAYS</b>, did you drink too much or get really drunk?
        </x-input.yes-no>
    </div>

    <div class="" x-data="{{ x_data_string($fields[5]['name'], 'drugs', false) }}">
        <x-input.number :question="$fields[5]" x-model="drugs">
            {!! $fields[5]['text'] !!}
        </x-input.number>

        <x-input.textarea x-show="drugs > 0" :question="$fields[6]">
            On average, when you used drugs in the <b>PAST 30 DAYS</b>, what and how much did you use?
        </x-input.textarea>
    </div>

    @for($i = 7; $i < 15; $i++)
        <x-input.number :question="$fields[$i]">
            {!! $fields[$i]['text'] !!}
        </x-input.number>
    @endfor

    <x-input.radio-group.horizontal :question="$fields[15]" />

    <x-input.radio-group.horizontal :question="$fields[16]" />

</x-layouts.survey>
