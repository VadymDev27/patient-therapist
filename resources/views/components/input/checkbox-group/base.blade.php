@props(['question'])
@php
    $default = [];
@endphp
<x-input.radio-group.base
    item="input.checkbox-group.item"
    :question="$question"
    :default-data="$default"
    {{ $attributes }}
>
    {{ $slot }}
</x-input.radio-group.base>
