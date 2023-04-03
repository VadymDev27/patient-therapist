<x-layouts.survey :step="$step">
    <h2 class="text-xl font-semibold">Diagnostic Screen</h2>
    <p>
        For the next section of the survey, please select a patient who meets criteria for Other Specified Dissociative Disorder (DSM-5), Dissociative Disorder, Unspecified (ICD-10), Dissociative Identity Disorder (DSM-5, ICD-10) or Posttraumatic Stress Disorder with Dissociative Symptoms (DSM-5, ICD-10) that:
    </p>
    <ul class="ml-4">
        <li>•	Has a trauma history</li>
        <li>•	has been actively engaged in treatment with you for at least the last three months</li>
        <li>•	is able to tolerate non-detailed references to: childhood and adult trauma; safety struggles and underlying reasons for these struggles; dissociation; as well as brief discussion of “parts of self”, even if this term does not apply to this client</li>
        <li>•	is able to read English at the eighth-grade level</li>
        <li>•	is over the age of 18</li>
        <li>•	has reliable high-speed access to the Internet (and if using a phone for access, has a data plan that can accommodate heavy data use)</li>
        <li>•	is able to watch videos and do written reflections and experiential exercises without high likelihood of becoming highly overwhelmed</li>
        <li>•	Is willing to commit to doing approximately 2 hours of work related to this study on a weekly basis for one year, followed by completing research surveys two times, six months apart</li>
        <li>•	Is willing to participate regardless of whether they are assigned to a six-month waiting period before accessing the study’s materials, or whether they are assigned to immediately get access to the study’s materials</li>
    </ul>

    <x-input.radio-group :question="$fields[0]">
        Which disorder does the client meet criteria for?
    </x-input.radio-group>

    <div class="pt-4">
        <table class="border border-gray-200 w-full">
        <thead>
            <tr>
                <th colspan="2" class="bg-gray-200 text-left pl-2">Please indicate if the following symptoms apply to your patient.
            </tr>
        </thead>
        <tbody>
            <x-input.table-row question-name="TSS_PTSD_1">
                <span class="font-bold">Exposure to actual or threatened death, serious injury, or sexual violence in one (or more) of the following ways:</span>
                    <ul class="ml-4">
                        <li>1. Directly experiencing the traumatic event(s).</li>
                        <li>2. Witnessing, in person, the event(s) as it occurred to others.</li>
                        <li>3. Learning that the traumatic event(s) occurred to a close family member or close friend.  </li>
                        <li>4. Experiencing repeated or extreme exposure to aversive details of the traumatic event(s)</li>
                    </ul>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_2">
                <span class="font-bold">Presence of one (or more) of the following intrusion symptoms associated with the traumatic event(s), beginning after the traumatic event(s) occurred: </span>
                <ul class="ml-4">
                    <li>1. Recurrent, involuntary, and intrusive distressing memories of the traumatic event(s).</li>
                    <li>2. Recurrent distressing dreams related to the traumatic event(s).</li>
                    <li>3. Dissociative reactions (e.g., flashbacks) in which the individual feels or acts as if the traumatic event(s) were recurring. </li>
                    <li>4. Intense or prolonged psychological distress at exposure to internal or external cues that symbolize or resemble an aspect of the traumatic event(s).</li>
                    <li>5. Marked psychological reactions to internal or external cues that symbolize or resemble an aspect of the traumatic event(s).</li>
                    </ul>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_3">
                <span class="font-bold">Persistent avoidance of stimuli associated with the traumatic event(s), beginning after the traumatic event(s) occurred, as evidenced by one or both of the following: </span>
                <ul class="ml-4">
                    <li>1. Avoidance of or efforts to avoid distressing memories, thoughts, or feelings about or closely associated with the traumatic event(s).</li>
                    <li>2. Avoidance of or efforts to avoid external reminders (people, places, conversations, activities, objects, situations) that arouse distressing memories, thoughts, or feelings about or closely associated with the traumatic event(s).</li>
                    </ul>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_4">
                <span class="font-bold">Negative alterations in cognitions and mood associated with the traumatic event(s), beginning or worsening after the traumatic event(s) occurred, as evidenced by two (or more) of the following: </span>
                <ul class="ml-4">
                    <li>1. Inability to remember an important aspect of the traumatic event(s).</li>
                    <li>2. Persistent and exaggerated negative beliefs or expectations about oneself, others, or the world (e.g., “I am bad,” “No one can be trusted,” “The world is completely dangerous”)</li>
                    <li>3. Persistent, distorted cognitions about the cause or consequences of the traumatic event(s) that lead the individual to blame himself/herself/themselves or others.</li>
                    <li>4. Persistent negative emotion state (e.g., fear, horror, anger, guilt, or shame).</li>
                    <li>5. Markedly diminished interest or participation in significant activities.</li>
                    <li>6. Feelings of detachment or estrangement from others.</li>
                    <li>7. Persistent inability to experience positive emotions (e.g., inability to experience happiness, satisfaction, or loving feelings).</li>
                </ul>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_5">
                <span class="font-bold">Marked alterations in arousal and reactivity associated with the traumatic event(s), beginning or worsening after the traumatic event(s) occurred, as evidenced by two (or more) of the following:</span>
                <ul class="ml-4">
                    <li>1. Irritable behavior and angry outbursts (with little or no provocation) typically expressed as </li>
                    <li>verbal or physical aggression toward people or objects.</li>
                    <li>2. Reckless or self-destructive behavior.</li>
                    <li>3. Hypervigilance.</li>
                    <li>4. Exaggerated startle response.</li>
                    <li>5. Problems with concentration.</li>
                    <li>6. Sleep disturbance (e.g., difficulty falling or staying asleep or restless sleep).</li>
                </ul>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_6">
                <span class="font-bold whitespace-pre-line">Duration is more than 1 month.
                    The disturbance causes distress or impairment.
                    The disturbance is not attributable to the effects of a substance (e.g., medication, alcohol) or another medical condition.</span>
            </x-input.table-row>
            <x-input.table-row question-name="TSS_PTSD_7">
                <span class="font-bold">Recurring symptoms of:</span>
                <ul class="ml-4">
                    <li>1. Depersonalization: Feeling detached from, and as if one were an outside observer of, one’s mental processes or body (e.g., feeling as though one were in a dream; feeling a sense of unreality of self or body or of time moving slowly).</li>
                    <li class="font-bold">OR/AND</li>
                    <li>2.	Derealization: Feelings of unreality of surroundings (e.g., the world around the individual is experienced as unreal, dreamlike, distant, or distorted).</li>
                </ul>
            </x-input.table-row>

            <x-input.table-row question-name="TSS_CPTSD_1">
                Severe and persistent problems in affect regulation
            </x-input.table-row>
            <x-input.table-row question-name="TSS_CPTSD_2">
                Severe and persistent beliefs about oneself as diminished, defeated or worthless, accompanied by feelings of shame, guilt or failure related to the traumatic event
            </x-input.table-row>
            <x-input.table-row question-name="TSS_CPTSD_3">
                Severe and persistent difficulties in sustaining relationships and in feeling close to others.
            </x-input.table-row>

            <x-input.table-row question-name="TSS_OSDD_1">
                Chronic and recurrent syndromes of mixed dissociative symptoms: This category includes identity disturbance associated with less-than-marked discontinuities in sense of self and agency, or alterations of identity or episodes of possession in an individual who reports no dissociative amnesia.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_OSDD_2">
                Identity disturbance due to prolonged and intense coercive persuasion: Individuals who have been subjected to intense coercive persuasion (e.g., brainwashing, thought reform, indoctrination while captive, torture, long-term political imprisonment, recruitment by sects/cults or by terror organizations) may present with prolonged changes in, or conscious questioning of, their identity.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_OSDD_3">
                Acute dissociative reactions to stressful events: This category is for acute, transient conditions that typically last less than 1 month, and sometimes only a few hours or days. These conditions are characterized by constriction of consciousness; depersonalization; derealization; perceptual disturbances (e.g., time slowing, macropsia); micro-amnesias; transient stupor; and/or alterations in sensory-motor functioning (e.g., analgesia, paralysis).
            </x-input.table-row>
            <x-input.table-row question-name="TSS_OSDD_4">
                Dissociative trance: This condition is characterized by an acute narrowing or complete loss of awareness of immediate surroundings that manifests as profound unresponsiveness or insensitivity to environmental stimuli. The unresponsiveness may be accompanied by minor stereotyped behaviors (e.g., finger movements) of which the individual is unaware and/or that he or she cannot control, as well as transient paralysis or loss of consciousness. The dissociative trance is not a normal part of a broadly accepted collective cultural or religious practice.
            </x-input.table-row>


            <x-input.table-row question-name="TSS_DID_1">
                Disruption of identity characterized by two or more distinct personality states, which may be described in some cultures as an experience of possession. The disruption in identity involves marked discontinuity in sense of self and sense of agency, accompanied by related alterations in affect, behavior, consciousness, memory, perception, cognition, and/or sensory-motor functioning. These signs and symptoms may be observed by others or reported by the individual.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_DID_2">
                Recurrent gaps in the recall of everyday events, important personal information, and/or traumatic events that are inconsistent with ordinary forgetting.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_DID_3">
                The symptoms cause clinically significant distress or impairment in social, occupational, or other important areas of functioning.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_DID_4">
                The disturbance is not a normal part of a broadly accepted cultural or religious practice.
            </x-input.table-row>
            <x-input.table-row question-name="TSS_DID_5">
                The symptoms are not attributable to the physiological effects of a substance (e.g., blackouts or chaotic behavior during alcohol intoxication) or another medical condition (e.g., complex partial seizures).
            </x-input.table-row>
        </tbody>
        </table>
    </div>
</x-layouts.survey>
