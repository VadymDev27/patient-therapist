@props([
    'questionName',
    'answerText',
    'xModel',
    'textInput' => '']
)
@php $xShow = "{$xModel}.includes('{$answerText}')"; @endphp
<x-input.radio-group.item
    type='checkbox'
    :question-name="$questionName.'[]'"
    :answer-text="$answerText"
    :text-input="$textInput"
    :x-model="$xModel"
    :x-show="$xShow"
    class="rounded disabled:opacity-50"
    {{ $attributes }}
>
{{ $slot }}
</x-input.radio-group.item>
