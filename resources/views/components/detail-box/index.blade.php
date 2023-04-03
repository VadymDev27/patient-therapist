<div class="border border-gray-300 rounded-lg p-4 min-w-max flex-grow">
    <div class="flex flex-col lg:flex-row justify-between gap-x-8 lg:items-center gap-y-4">
        {{ $icon ?? '' }}
        <div class="flex flex-grow flex-col lg:flex-row gap-x-16 items-start flex-nowrap justify-start">
            {{ $slot }}
        </div>
        <div {{ $buttons->attributes->class(['flex flex-col justify-end gap-y-1'])->merge([]) }}>
            {{ $buttons }}
        </div>
    </div>
</div>
