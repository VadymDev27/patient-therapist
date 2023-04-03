<x-layouts.survey :step="$step">
    <x-input.heading>Difficulties in Emotion Regulation Scale</x-input.heading>
    <div class="flex flex-col mb-4">
        <div>Please indicate how frequently the statements below apply to you <u>in the last month</u> using the following response categories:</div>
        <div class="self-center">
            <div>1 = Almost never (0-10%)</div>
            <div>2 = Sometimes (11-35%)</div>
            <div>3 = About half the time (36-65%)</div>
            <div>4 = Most of the time (66-90%)</div>
            <div>5 = Almost always (91-100%)</div>

        </div>

    </div>

    @foreach ($fields as $question)
        <x-input.radio-group.side :question="$question" />
    @endforeach
</x-layouts.survey>
