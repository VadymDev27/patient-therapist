@props(['questionName', 'xModel' => 'textarea', 'hasOutsideData' => false, 'rows' => '3'])

<div x-data="{{ x_data_string($questionName, $xModel, $hasOutsideData) }}" {{ $attributes->merge(['class' => 'flex-1'])}}>
    <div>{{ $slot }}</div>
    <div class="max-w-xl">
        <textarea name="{{ $questionName }}" class="p-0 w-full" rows="{{ $rows}}" x-model="{{ $xModel }}">
        </textarea>
    </div>
</div>
