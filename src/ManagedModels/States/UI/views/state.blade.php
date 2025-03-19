<div>
    {!! $this->getStateLabel() !!}

    @if($this->isAllowedToEdit())
        <x-chief::button wire:click="edit" class="cursor-pointer text-xs">
            Aanpassen
        </x-chief::button>
    @endif

    <livewire:chief-wire::edit-state :key="$modelReference . $stateKey"
                                     :parent-component-id="$this->getId()"
                                     :state-key="$stateKey"
                                     :model-reference="$modelReference" />
</div>

