@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;

    $wireModel = LivewireFieldName::get($getName($locale ?? null));

@endphp

<livewire:chief-wire::repeat
    wire:key="{{ $wireModel }}"
    :field="$field"
    :locale="$locale ?? null"
    :parent-component-id="$this->getId()"
    wire:model="{{ $wireModel }}"
/>
