@php use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName; @endphp

<x-chief::multiselect
        wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        name="{{ $getName($locale ?? null) . ($allowMultiple() ? '[]' : '') }}"
        :options="$getMultiSelectFieldOptions()"
        :multiple="$allowMultiple()"
        :selection="$getActiveValue($locale ?? null)"
/>

