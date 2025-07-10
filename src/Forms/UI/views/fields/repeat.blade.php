<livewire:chief-wire::repeat
    wire:key="{{ $getWireModelValue($locale ?? null) }}"
    :field="$field"
    :locale="$locale ?? null"
    :parent-component-id="isset($this) ? $this->getId() : null"
    wire:model="{{ $getWireModelValue($locale ?? null) }}"
/>
