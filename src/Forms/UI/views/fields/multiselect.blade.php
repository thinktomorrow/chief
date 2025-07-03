@php
    $modelBindingType = $getWireModelType() == 'defer' ? 'wire:model' : 'wire:model.' . $getWireModelType();
    $modelBinding = [$modelBindingType => Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName::get($getName($locale ?? null))];
@endphp

<x-chief::multiselect
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :dropdown-position="$getDropdownPosition()"
    :attributes="$attributes
            ->merge($getCustomAttributes())
            ->merge($modelBinding)"
/>
