<x-layouts.survey :step="$step">
    <div class="lg:mx-12 sm:mx-8">
        <h2 class="text-lg text-center font-bold sm:px-8 my-4">Treatment of Patients with Dissociative Disorders (TOP  DD) Network Randomized Control Study</h2>

        <div class="grid sm:grid-rows-2 grid-rows-4 grid-flow-col">
            <div><b>Principal investigator</b>: Bethany Brand</div>
            <div><b>Phone number</b>: (410) 704-3067</div>
            <div><b>Email</b>: bbrand@towson.edu</div>
            <div><b>Email</b>: TOPDD@towson.edu</div>
        </div>

        <div class="font-bold text-center py-4 text-lg">{{ $step['surveyTitle'] }}</div>

        @error('survey')
            <div class="text-red-500 mb-4">{{ $message }}</div>
        @enderror

        <div>
            Prior to being able to participate in this study, participants must complete the following quiz to ensure they understand the possible risks, benefits and procedures for the study. You must get at least 8 out of the 10 questions correct before being able to proceed to participate in the study.  You may take the quiz multiple times if needed so that you get at least 8 out of 10 questions correct.
        </div>

        <div class="mt-4">
            Indicate if each of the following questions is true or false:
        </div>


        @foreach ($fields as $question)
            <x-input.yes-no left="True" right="False" :question="$question" />
        @endforeach

        <div class="mt-4 font-bold">
            By continuing to fill out this survey, I am indicating my understanding that (a) I am participating in a research study; (b) my participation is completely voluntary and that I can withdraw my consent at any time without penalty; and (c) I do not have to answer any questions I do not want to answer.  
        </div>
    <div>
</x-layouts.survey>
