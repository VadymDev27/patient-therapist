@props(['stripe' => false])

<td {{ $attributes->class([
    'border',
    'border-gray-300',
    'px-4',
    'py-2',
    'bg-gray-200' => $stripe])->merge([]) }}>{{ $slot }}
</td>
