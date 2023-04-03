<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Demographic, Background, Treatment and Training Experience</h2>
    <x-input.radio-group :question="$fields[0]">
        Please select your discipline:
    </x-input.radio-group>

    <x-input.checkbox-group :question="$fields[1]">
        Sex and Gender Identification  (select any/all that apply):
    </x-input.checkbox-group>

    <x-input.number :question="$fields[2]">
        Your age:
    </x-input.number>

    <x-input.checkbox-group :question="$fields[3]">
        Racial/Ethnic Background  (select any/all that apply):
    </x-input.checkbox-group>

    <x-input.country :question="$fields[4]">
    In what country do you practice?
    </x-input.country-selector>

    <x-input.radio-group :question="$fields[5]">
        How would you describe your main theoretical orientation?<br>(If eclectic, please indicate the approach that most informs your work):
    </x-input.radio-group>


    <x-input.checkbox-group :question="$fields[6]">
        In what settings do you work? (check all that apply):
    </x-input.checkbox-group>

    <x-input.number :question="$fields[7]">
        Number of years in practice:
    </x-input.number>

    <x-input.number :question="$fields[8]">
        Number of years treating trauma patients:
    </x-input.number>

    <x-input.number :question="$fields[9]">
        Number of years treating dissociative disorder patients:
    </x-input.number>

    <x-input.number :question="$fields[10]">
        Approximate number of dissociative disorder patients I have treated for longer than one year:
    </x-input.number>
    <x-input.radio-group.horizontal :question="$fields[11]">
        How prepared do you feel to treat patients with dissociative disorders at this time?
    </x-input.radio-group.horizontal>

    <x-input.radio-group.horizontal :question="$fields[12]">
        How comfortable do you feel to treat patients with dissociative disorders at this time?
    </x-input.radio-group.horizontal>

    {{-- QUESTION 14 --}}
    @php
        $question = $fields[13];
        $default = [];
        $x_data = x_data_string($fields[15]['name'], 'checkboxGroup', false, []);
    @endphp

    <x-input.survey-question :question="$question" x-model="data" :default-data="$default">
        <div>
            <div>
                We are interested in how you learned about treating trauma and dissociative patients. Check all that apply.
            </div>

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][0]"
                x-model="data"
            />

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][1]"
                x-model="data"
            />

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][2]"
                x-model="data"
                with-conditional
            >
                <x-input.text.base
                    :question-name="$fields[14]['name']"
                    class="ml-10"
                    type="number"
                >
                        Approximate number of hours supervised by trauma disorders/DD specialist:
                </x-input.text.base>
            </x-input.checkbox-group.item>

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][3]"
                x-model="data"
            />

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][4]"
                x-model="data"
                with-conditional
            >
                <div x-data="{{$x_data}}" class="ml-10">
                    @foreach($fields[15]['options'] as $option)
                    <x-input.checkbox-group.item
                        :question-name="$fields[15]['name']"
                        :answer-text="$option"
                        x-model="checkboxGroup"
                    />
                    @endforeach
                </div>
            </x-input.checkbox-group.item>

            @foreach (array_slice($question['options'],4,4,true) as $index => $answer)
            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$answer"
                x-model="data"
            />
            @endforeach

            <x-input.checkbox-group.item
                :question-name="$question['name']"
                :answer-text="$question['options'][8]"
                x-model="data"
                with-conditional
            >
                <x-input.text.base
                    :question-name="$fields[16]['name']"
                    class="ml-10"
                    type="number"
                >
                    List:
                </x-input.text.base>
            </x-input.checkbox-group.item>
        </div>
    </x-input.survey-question>

</x-layouts.survey>
