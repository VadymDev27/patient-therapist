@props(['question'])
@php
    $default = [];
@endphp
<x-input.radio-group
    item="input.checkbox-group.item"
    :question="$question"
    :default-data="$default"
>
    {{ $slot }}
</x-input.radio-group>
