<x-layouts.survey :step="$step">
    <x-input.heading>Intervention and Study Feedback</x-input.heading>
    <div>We’re very interested in your feedback about the program and this study; we use this feedback to inform future iterations of this work.</div>

    @for($i = 0; $i < 7; $i++)
        <x-input.radio-group :question="$fields[$i]" />
    @endfor

    <x-input.textarea :question="$fields[7]" />

    <x-input.textarea :question="$fields[8]" />
</x-layouts.survey>
