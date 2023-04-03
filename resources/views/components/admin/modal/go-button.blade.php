@props(['positive' => false])
@php
    $accent = $positive
                ? 'bg-logo-blue hover:bg-blue-800 focus:ring-blue-500'
                : 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
    $class = $accent . ' w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white  focus:outline-none focus:ring-2 focus:ring-offset-2  sm:ml-3 sm:w-auto sm:text-sm';
@endphp

<button class="{{ $class }}" id="submit-anyway-button" {{ $attributes->whereStartsWith('x-') }}>
    {{ $slot }}
</button>
