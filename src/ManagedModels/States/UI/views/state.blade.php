<div>
    @if ($this->isAllowedToEdit())
        <x-chief::button wire:click="edit" :variant="$this->getStateVariant()">
            <span>{{ $this->getStateLabel() }}</span>
            <x-chief::icon.chevron-down />
        </x-chief::button>
    @endif

    <livewire:chief-wire::edit-state
        :key="$modelReference . $stateKey"
        :parent-component-id="$this->getId()"
        :state-key="$stateKey"
        :model-reference="$modelReference"
    />
</div>
