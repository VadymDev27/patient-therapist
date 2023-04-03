@props(['noGap' => false])

<button {{ $attributes->class(['mt-4' => ! $noGap])->merge(['type' => 'submit', 'class' => 'inline-block items-center text-base px-4 py-2 leading-none rounded text-white border-transparent bg-logo-blue hover:bg-indigo-900']) }}>
    {{ $slot }}
</button>
