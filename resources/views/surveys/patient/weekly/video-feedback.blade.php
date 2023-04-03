@php
    $questions = [
        'The video addressed problems and skills I need to work on.',
        'I understood the information presented in the video.',
        'This video gives me hope that I can learn things that will help me.'
    ];
@endphp

<x-layouts.survey :step="$step">
    <x-input.heading>Video Feedback</x-input.heading>

    @for($i = 0; $i < 3; $i++)
        <x-input.radio-group :question="$fields[$i]">
            {{ $questions[$i] }}
        </x-input.radio-group>
    @endfor

    <x-input.textarea :question="$fields[3]">
        Optional: Other feedback about the video?
    </x-input.textarea>
</x-layouts.survey>
