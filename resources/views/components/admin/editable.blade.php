@props(['text'])

<div x-data="{'text': '{{ $text }}'}">
    <span x-text="text" x-show="! editing"></span>
    <input type="text" x-model="text" x-show="editing" />
</div>
