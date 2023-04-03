@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-lg font-semibold leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-lg font-semibold leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<style>
</style>

<button {{ $attributes->merge(['class' => 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-lg font-semibold leading-5 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out' ]) }}>
    {{ $slot }}
</button>
