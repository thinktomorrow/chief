@php
    use Thinktomorrow\Chief\Forms\Fields\FieldName\LivewireFieldName;

    // Check if component is used inside a parent Livewire component (such as AddFragment)
    $insideComponent = isset($this) && method_exists($this, 'getId');
@endphp

<livewire:chief-wire::repeat
    :field="$field"
    :locale="$locale ?? null"
    parent-component-id="{{ $insideComponent ? $this->getId() : null }}"
    wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
/>
