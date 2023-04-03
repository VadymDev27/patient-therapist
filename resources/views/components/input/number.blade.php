@props(['question'])

<x-input.text type="number" :question="$question" {{ $attributes }}>
    {{ $slot }}
</x-input.text>
