@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<x-chief::form.input.select
    id="{{ $getElementId($locale ?? null) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :multiple="$allowMultiple()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge($modelBinding)"
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
