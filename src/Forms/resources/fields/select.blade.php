@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::form.input.select
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :multiple="$allowMultiple()"
>
    <option value="">---</option>

    @if ($hasOptionGroups($locale ?? null))
        @foreach ($getOptions() as $optionGroup)
            <optgroup label="{{ $optionGroup['label'] }}">
                @foreach ($optionGroup['options'] as $option)
                    <option
                        {{ in_array($option['value'], (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }}
                        value="{{ $option['value'] }}"
                    >
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    @else
        @foreach ($getOptions() as $option)
            <option
                {{ in_array($option['value'], (array) $getActiveValue($locale ?? null)) ? 'selected' : '' }}
                value="{{ $option['value'] }}"
            >
                {{ $option['label'] }}
            </option>
        @endforeach
    @endif
</x-chief::form.input.select>
