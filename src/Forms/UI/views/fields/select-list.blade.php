@php use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName; @endphp

<x-chief-form::select-list
    wire:model.change="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :grouped="$hasOptionGroups()"
/>

