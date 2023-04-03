@props(['yearQuestion', 'monthQuestion', 'models' => []])

@php
    if ($models) {
        $x_data = '{}';
        $yearModel = $models[0];
        $monthModel = $models[1];
    } else {
        $month = json_encode(old($monthQuestion['name']) ?? '');
        $year = json_encode(old($yearQuestion['name']) ?? '' );
        $x_data = "{ year: {$year}, month: {$month} }";
        $yearModel = 'year';
        $monthModel = 'month';
    }
@endphp

<div {{ $attributes->merge(['class' => 'm-0 py-2 flex flex-row', 'x-data' => $x_data]) }}
    @if($yearQuestion['required'])
    {{-- we can safely assume that if the year field is required, the month probably is too --}}
    :class="{{"(highlightIncomplete && ({$yearModel}.length === 0 || {$monthModel}.length === 0)) && 'bg-blue-200'"}}"
    @endif
>
    <div class="pr-2 text-left">
        {{ $yearQuestion['number'] . '.' }}
    </div>
    <div>
        <div>
            {{ $slot }}
        </div>
        <div class="m-2">
        <select
            class="border-0 border-b"
            name="{{ $yearQuestion['name'] }}"
            x-model="{{ $yearModel }}"
            >
            <option value="" disabled></option>
            @foreach(range(0,100) as $num)
                <option value="{{ $num }}" >{{ $num }}</option>
            @endforeach
        </select> years,
        <select
            class="border-0 border-b"
            name="{{ $monthQuestion['name']}}"
            x-model="{{ $monthModel }}"
            >
            <option value=""></option>
            @foreach(range(0,12) as $num)
            <option value="{{ $num }}" >{{ $num }}</option>
            @endforeach
        </select> months
    </div>
    </div>
</div>

