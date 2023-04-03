@props(['number', 'prep' => false])
@php
    $x = $prep ? 'prep' : 'week';
    $componentName = "video.transcript.{$x}-{$number}";
@endphp

<div {{ $attributes->merge([]) }} >
    <x-dynamic-component :component="$componentName" />
</div>
