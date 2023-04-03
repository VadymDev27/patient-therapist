<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Demographics</h2>

    {{-- Settings --}}
    @php
        $prefix = 'PSS_Demographics_';

        $q2 = ['Woman', 'Man', 'Nonbinary', 'Trans', 'Other'];
        $q3 = ['Caucasian', 'Asian', 'Latino / Hispanic', 'African', 'Native / Aboriginal / Indigenous / First People', 'Other'];
        $q4 = ['Married (first marriage)', 'Married (remarried)','Divorced','Separated','Widowed','In a relationship', 'Not currently in a relationship', 'Never involved with a long-term partner'];
        $q5 = ['Grade school','High school graduate or equivalent','Some college/university'];
        $scaleOptions = ([
            '1' => '(Not at all)',
            '2' => '',
            '3' => '(Fairly)',
            '4' => '',
            '5' => '(Completely)'
    ]);
    @endphp

    <x-input.number :question="$fields[0]">
        Your age:
    </x-input.number>

    <x-input.checkbox-group :question="$fields[1]">
        Sex and Gender Identification  (select any/all that apply):
    </x-input.checkbox-group>

    <x-input.checkbox-group :question="$fields[2]">
        Racial/Ethnic Background  (select any/all that apply):
    </x-input.checkbox-group>

    <x-input.radio-group :question="$fields[3]">
        Marital status:
    </x-input.radio-group>

    <x-input.radio-group.base :question="$fields[4]" x-model="education">
        Highest level of completed education:

        <x-slot name="options">
            <x-input.radio-group.item
                :question-name="$fields[4]['name']"
                answer-number="1"
                answer-text="Grade"
                x-model="education"
            >
                <x-slot name="inline">
                    <input
                        type="number"
                        class="p-0 my-0 border-0 border-b w-12 max-h-5"
                        name={{ $fields[5]['name'] }}
                        value={{ old($fields[5]['name'])}}
                    >
                </x-slot>
            </x-input.radio-group.item>

            @foreach ($fields[4]['options'] as $option)
                <x-input.radio-group.item
                    :question-name="$fields[4]['name']"
                    :answer-number="$loop->iteration"
                    :answer-text="$option"
                    x-model="education"
                />
            @endforeach

            <x-input.radio-group.item
                :question-name="$fields[4]['name']"
                :answer-number="99"
                answer-text="Other"
                x-model="education"
                :text-input="$fields[6]['name']"
            />
        </x-slot>
    </x-input.radio-group.base>


    <h2 class="text-xl font-semibold mt-4">Work and Disability</h2>
    @php
        $prefix = "PSS_WorkDisability_";
        $q1 = ['Part-time work','Full-time work','Part-time school','Full-time school','Homemaker','Unemployed'];
        $q4 = ['Medical reasons', 'Psychological reasons','Both'];
        $q5 = ['Full disability support','Partial disability support'];
    @endphp

    <x-input.checkbox-group :question="$fields[7]">
        Current employment status (check all that apply):
    </x-input.checkbox-group>

    <x-input.yes-no.conditional :question="$fields[8]">
        Are you recieving disability support?
        <x-slot name="dependents">
            <x-input.duration :year-question="$fields[9]" :month-question="$fields[10]" question-number="3">
                How long have you been receiving disability support?
            </x-input.duration>

            <x-input.radio-group :question="$fields[11]">
                Are you receiving disability support for medical reasons, psychological reasons, or both?
            </x-input.radio-group>

            <x-input.radio-group :question="$fields[12]">
                What level of disability support do you receive?
            </x-input.radio-group>
        </x-slot>
    </x-input.yes-no.conditional>
</x-layouts.survey>
