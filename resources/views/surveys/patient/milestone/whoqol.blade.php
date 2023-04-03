@php
    $prefix = 'QOL_';

    $options1 = [
        1 => '(Very poor)',
        2 => '(Poor)',
        3 => '(Neither poor nor good)',
        4 => '(Good)',
        5 => '(Very good)'
    ];

    $options2 = [
        1 => '(Very dissatisfied)',
        2 => '(Dissatisfied)',
        3 => '(Neither satisfied nor dissatisfied)',
        4 => '(Satisfied)',
        5 => '(Very satisfied)'
    ];
@endphp


<x-layouts.survey :step="$step">
    <x-input.heading>Quality of Life Brief Scale</x-input.heading>
    <div class="flex flex-col mb-4">
        <div>This questionnaire asks how you feel about your quality of life, health, or other areas of your life. Please answer all the questions. If you are unsure about which response to give to a question, please choose the one that appears most appropriate. This can often be your first response. Please keep in mind your standards, hopes, pleasures and concerns. We ask that you think about your life in the <u><b>last TWO (2) WEEKS</b></u>.</div>
        <div>Please read each question, assess your feelings, and select the option on the scale that gives the best answer for you for each question.</div>
    </div>

    @foreach($fields as $question)
        <x-input.radio-group.horizontal :question="$question" />
    @endforeach
</x-layouts.survey>
