@php
    $questions = [
        'I used grounding techniques to help myself when feeling too much (intense feelings or impulses) or too little. (Grounding examples: Orienting to the present date, time, situation, and my biological age; anchoring in the present by paying attention to what I notice with my five senses.) ',
        'I worked to notice and pay attention to how things are different in the “here and now” from the “there and then” when I was hurt.',
        'I managed intrusive memories, images, and flashbacks using containment strategies (e.g., imagery techniques used to contain PTSD symptoms). ',
        'I used recovery-focused coping skills to help myself when feeling too much or too little instead of acting on impulses to do risky, unhealthy, or unsafe things. ',
];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Coping Skill Use</x-input.heading>
    <div>
        The questions below are about the use of recovery-focused coping skills.  Please select the option that best describes your use of that coping skill in the <u><b>last seven days</b></u>:
    </div>

    @for($i = 0; $i < 4; $i++)
        <x-input.radio-group.horizontal :question="$fields[$i]">
            {{ $questions[$i] }}
        </x-input.radio-group-horizontal>
    @endfor
</x-layouts.survey>
