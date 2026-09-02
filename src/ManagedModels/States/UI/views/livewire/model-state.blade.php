<div>
    @if ($this->isAllowedToEdit())
        <x-chief::button wire:click="edit" :variant="$this->getStateVariant()">
            <span>{{ $this->getStateLabel() }}</span>
            <x-chief::icon.chevron-down />
        </x-chief::button>
    @endif

    <template x-teleport="body">
        <livewire:chief-wire-states::edit-model-state
            :key="$modelReference . $stateKey"
            :parent-component-id="$this->getId()"
            :state-key="$stateKey"
            :model="$this->getModel()"
        />
    </template>
</div>
