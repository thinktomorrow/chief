<div>

    @if ($this->isAllowedToEdit())
        <x-chief-table::button wire:click="edit" variant="outline-blue">
            <span>{{ strip_tags($this->getStateLabel()) }}</span>
            <x-chief::icon.chevron-down />
        </x-chief-table::button>
    @endif

    <livewire:chief-wire::edit-state :key="$modelReference . $stateKey"
                                     :parent-component-id="$this->getId()"
                                     :state-key="$stateKey"
                                     :model-reference="$modelReference" />


    {{--    <x-chief-table::button variant="blue">--}}
    {{--        <span>Publiceer</span>--}}
    {{--        <x-chief::icon.solid.sent />--}}
    {{--    </x-chief-table::button>--}}
</div>
