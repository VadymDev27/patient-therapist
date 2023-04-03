<x-layouts.survey :step="$step">
    <x-input.heading>Disorder Diagnosis Update</x-input.heading>

    <x-input.radio-group :question="$fields[0]">
        Which disorder does this client meet criteria for?
    </x-input.radio-group>

    @php
    $questions=[
        'Physical assault or abuse?',
        'Sexual assault or abuse?',
        'Emotional and/or verbal abuse?',
        'Health problems?',
        'Self-neglect?',
        'Fear of change?',
        'Resistance or retaliation among parts of self?',
        'Financial difficulties?',
        'Difficulty with housing?',
        'Difficulties with family of origin?',
        'Difficulties within marriage or relationship with significant other?',
        'Difficulties with biological or adopted children?',
        'Difficulties at work?',
        'Difficulties at school?',
        'Difficulties are volunteer job?',
        'Difficulty with mistrust towards me, the therapist?',
        'Difficulty with mistrust towards other members of the treatment team (e.g. psychiatrist, therapy group leader)?',
        'Other stressors:'
    ];
    @endphp
    <x-input.heading>Patient Stressors</x-input.heading>
    <p>In the last 6 months, has the patient experienced:</p>

    @foreach ($questions as $question)
        <x-input.yes-no with-unclear :question="$fields[$loop->iteration]">
            {{ $question }}
        </x-input.yes-no>
    @endforeach


</x-layouts.survey>
