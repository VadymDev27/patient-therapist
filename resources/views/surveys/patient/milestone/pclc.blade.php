<x-layouts.survey :step="$step">
    <x-input.heading>PTSD Checklist - Civilian Version</x-input.heading>
    <div class="flex flex-col mb-4">
        <div><u>Instructions</u>: Below is a list of problems that people sometimes have in response to a very stressful experience. Please read each problem carefully and then circle one of the numbers to the right to indicate how much you have been bothered by that problem <u>in the last month</u>.</div>
        <div class="self-center">
            <div>0 = Not at all</div>
            <div>1 = A little bit</div>
            <div>2 = Moderately</div>
            <div>3 = Quite a bit</div>
            <div>4 = Extremely</div>

        </div>

    </div>

    <div><b><i>In the past month, how much were you bothered by:</i></b></div>

    @foreach ($fields as $question)
        <x-input.radio-group.side :question="$question" />
    @endforeach

</x-layouts.survey>
