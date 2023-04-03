<x-layouts.survey :step=$step>
    <h2 class="text-xl font-semibold">Co-occurring diagnoses</h2>

    @php
    $examples = [
        [
        '(DSM-5 examples: Disruptive Mood Dysregulation Disorder,
        Major Depressive Disorder,
        Persistent Depressive Disorder / Dysthymia,
        Premenstrual Dysphoric Disorder,
        Other Depressive Disorder)',
        '(DSM-5 examples: Bipolar I Disorder,
        Bipolar II Disorder,
        Cyclothymic Disorder,
        Other Bipolar Disorder)',
        '(DSM-5 examples:
        Obsessive-Compulsive Disorder
        Body Dysmorphic Disorder
        Hoarding Disorder
        Trichotillomania (Hair-Pulling Disorder)
        Excoriation (Skin-Picking) Disorder
        Other Obsessive-Compulsive and Related Disorder)',
        '(DSM-5 examples: Somatic Symptom Disorder,
        Illness Anxiety Disorder,
        Conversion Disorder (Functional Neurological Symptom Disorder),
        Psychological Factors Affecting Other Medical Conditions,
        Factitious Disorder,
        Other Somatic Symptom and Related Disorder)',
        '(DSM-5 examples: Substance Use Disorder
        Substance/Medication-Induced Mental Disorder)',
        '(DSM-5 examples: Schizotypal Disorder, Delusional Disorder, Brief Psychotic Disorder, Schizophreniform Disorder, Schizophrenia, Schizoaffective Disorder, Other Psychotic Disorder)',
        '',
        '',
        '(DSM-5 examples: Reactive Attachment Disorder,
        Disinhibited Social Engagement Disorder,
        Acute Stress Disorder,
        Adjustment Disorders,
        Other Specified Trauma- and Stressor-Related Disorder',
        '',
        '(DSM-5 examples: Separation Anxiety Disorder,
        Selective Mutism,
        Specific Phobia,
        Social Anxiety Disorder (Social Phobia),
        Panic Disorder,
        Panic Attack (Specifier),
        Agoraphobia,
        Generalized Anxiety Disorder,
        Other Anxiety Disorder)'
],
    ['(The “Odd, Eccentric Cluster”; e.g., Paranoid, Schizoid, Schizotypal PD)',
            '(The “Dramatic, Emotional, Erratic” Cluster; e.g., Narcissistic, Antisocial, Borderline, Histrionic)',
            '(The “Anxious, Fearful” Cluster; e.g., Dependent, Obsessive Compulsive, Avoidant)',
            ''
        ]
];

    $questionText = [
        'Please review the diagnostic categories listed below, and indicate all categories the patient meets DSM-5, ICD-10, or ICD-11 criteria for.  (Check all categories that apply.)',
        'Please indicate whether the patient currently meets DSM-5 or ICD-10 criteria for any of the personality disorder categories listed below. Check all that apply.'
    ];
    @endphp
    @for($i = 0; $i < 2; $i++)
    @php
        $question = $fields[$i];
    @endphp

    <x-input.survey-question :question="$question" x-model="data" array>
        <div class="flex flex-col gap-y-3">
            <div class="font-semibold">
                {{ $questionText[$i] }}
            </div>

            @foreach($examples[$i] as $example)
            <div>
                <x-input.checkbox-group.item
                    :question-name="$question['name']"
                    :answer-text="$question['options'][$loop->index]"
                    x-model="data" />
                <div class="italic ml-8">{{ $example }}</div>
            </div>
            @endforeach
        </div>
    </x-input.survey-question>
    @endfor

    <h2 class="text-2xl font-bold mt-6">Patient Functioning</h2>

    <x-input.radio-group.horizontal :question="$fields[2]">
        Typical quality of relationships in the last 6 months (select number):
    </x-input.radio-group.horizontal>

    <x-input.radio-group.horizontal :question="$fields[3]">
        Typical employment status in the last 6 months (select number):
    </x-input.radio-group.horizontal>
</x-layouts.survey>
