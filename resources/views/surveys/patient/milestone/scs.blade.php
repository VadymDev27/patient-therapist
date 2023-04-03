<x-layouts.survey :step="$step">
    <x-input.heading>Self-Compassion Scale</x-input.heading>
    <div class="flex flex-col mb-4">
        <div>HOW I TYPICALLY ACT TOWARDS MYSELF IN DIFFICULT TIMES</div>
        <div>Please read each statement carefully before answering. For each item, indicate how often you behave in the stated manner <u>in the last month</u>:</div>
    </div>

    @foreach ($fields as $question)
        <x-input.radio-group.horizontal :question="$question" />
    @endforeach
</x-layouts.survey>
