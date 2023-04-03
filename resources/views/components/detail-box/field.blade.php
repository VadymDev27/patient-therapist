@props(['label'])

<div class="flex flex-row gap-x-4 items-center lg:items-baseline lg:flex-col">
    <div class="lg:text-sm font-semibold p-0">{{ trim($label) }}<span class='lg:hidden'>:</span>
    </div>
    <div>{{ $slot }}</div>
</div>
