@props(['question'])

<div class="">
    @if($question['text'])
        {!! $question['text'] !!}
    @else
    {{ $slot }}
    @endif
</div>
