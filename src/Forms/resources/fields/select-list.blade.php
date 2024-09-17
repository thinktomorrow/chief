@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp

<x-chief-form::select-list
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
    name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
    :options="$getMultiSelectFieldOptions()"
    :multiple="$allowMultiple()"
    :selection="$getActiveValue($locale ?? null)"
    :grouped="$hasOptionGroups()"
/>

