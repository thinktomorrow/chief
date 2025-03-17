@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<x-chief::multiselect
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :dropdown-position="$getDropdownPosition()"
/>
