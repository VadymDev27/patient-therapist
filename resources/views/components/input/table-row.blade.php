@props(['questionName'])

<tr x-data="{ input: {{ json_encode(old($questionName) ?? '') }} }"
    :class="(highlightIncomplete && !input) && 'bg-blue-200'">
    <td class="border border-gray-200 p-2 align-top md:w-20">
        <div>
            <input type="radio" name="{{ $questionName }}" id="{{ $questionName . '_Yes' }}" value="Yes" x-model="input"/>
            <label for="{{ $questionName . '_Yes' }}">Yes</label>
        </div>
        <div>
            <input type="radio" name="{{ $questionName }}" id="{{ $questionName . '_No' }}" value="No" x-model="input"/>
            <label for="{{ $questionName . '_No' }}">No</label>
        </div>
    </td>
    <td class="border p-2 align-top">
        {{ $slot }}
    </td>
</tr>
