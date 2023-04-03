@php
$isFile = $attributes->get('type') === 'file';
@endphp
<div class="text-gray-700 md:flex md:items-center mt-2">
    <div class="mb-0 md:w-60 md:text-right md:px-4">
        <label>{{ $slot }}</label>
    </div>
    <div class="md:w-2/3">
        <input {{ $attributes->class([
            'md:mt-1',
        'show',
        'w-full',
        'rounded-md',
        'border',
        'border-gray-300',
        'shadow-sm',
        'focus:border-indigo-300', 'focus:ring', 'focus:ring-indigo-200' ,'focus:ring-opacity-50v',
        'p-1' => $isFile
        ])->merge([
            'type' => 'text'
        ]) }}/>
    </div>
</div>
