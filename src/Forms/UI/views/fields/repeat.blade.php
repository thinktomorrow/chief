<livewire:chief-wire-form::repeat-field-editor
    wire:key="{{ $getWireModelValue($locale ?? null) }}"
    :field="$field"
    :locale="$locale ?? null"
    :parent-component-id="isset($this) ? $this->getId() : null"
    wire:model="{{ $getWireModelValue($locale ?? null) }}"
/>
