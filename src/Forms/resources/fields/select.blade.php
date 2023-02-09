<x-chief::input.select
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :multiple="$allowMultiple()"
>
    <option value="">---</option>

    @foreach ($getOptions() as $key => $value)
        <option {{ in_array($key, (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }} value="{{ $key }}">
            {{ $value }}
        </option>
    @endforeach
</x-chief::input.select>
