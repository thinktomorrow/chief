@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;
@endphp

<livewire:chief-wire::repeat
    :field="$field"
    :locale="$locale ?? null"
    :parent-component-id="$this->getId()"
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
/>
