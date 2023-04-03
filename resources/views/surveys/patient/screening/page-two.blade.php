<x-layouts.survey :step="$step">
<h2 class="text-2xl font-semibold">Study Eligibility Criteria</h2>

    @php
        $prefix = 'PSS_Eligibility_';
        $questions = [
        'I can read and understand English at the eighth-grade level.',
        'I can tolerate hearing and thinking about general references to: childhood and adult trauma; safety struggles and reasons for these struggles; dissociation; and occasional brief discussions of “parts of self”, even if this term does not apply to me.',
        'I have been in treatment with my therapist for at least three months.',
        'I have reliable high-speed access to the Internet (and if using a phone for access, I have a data plan that can accommodate heavy data use).',
        'I realize that I must be in, and remain in, individual therapy with the therapist who is signing up with me to participate in this study.  I will contact the study via a link on the study’s website if I discontinue working with my current therapist.',
        'I am willing to do approximately 2 ½ hours of work each week related to the study to learn and practice things that will help me.  This will include watching brief educational videos, doing written reflection exercises, and doing daily behavioral practice exercises.',
        'I am willing to complete surveys that may take up to 2 hours to complete at the beginning, middle, and end of the study.  I am also willing to complete brief weekly questionnaires that may take approximately 30 minutes.',
        'If you find yourself “needing” to do things that people consider to be risky, unhealthy, or unsafe  (Examples: Hurt yourself, eat too little or too much, drink too much alcohol, use drugs, etc.) are you willing to work on trying to reduce these kinds of behaviors?  ',
        'Half of the people who sign up for the study will be randomly assigned to wait for six months before starting the educational program.  Are you willing to stay in the study and wait six months before starting the program if you get assigned to the wait list group?  ',
        'If you are assigned to start the educational program rather than being on the wait list, are you willing to start the program right away?'
    ];
    @endphp

    @foreach ($questions as $question)
        <x-input.yes-no :question="$fields[$loop->index]">
            {{ $question }}
        </x-input.yes-no>
    @endforeach

</x-layouts.survey>

