@props(['questionName', 'type'])
@php
    $model = $attributes->get('x-model') ?? 'input';
    $data = json_encode(old($questionName) ?? '' );
    $x_data = $attributes->has('x-model')
                ? '{}'
                : "{ {$model}: $data }";
@endphp

<div {{ $attributes
    ->whereDoesntStartWith('x-on')
    ->merge(['x-data' => $x_data, 'class' => 'border-none']) }}
    x-id="['input']"
>
    <label :for="$id('input')" class="items-center">
        {{ $slot }}
    </label>
    <input
        type="{{ $type ?? 'text'}}"
        name="{{ $questionName}}"
        :id="$id('input')"
        x-model="{{ $model }}"
        class="border-0 border-b border-grey-dark p-0 mx-2"
    />
</div>
