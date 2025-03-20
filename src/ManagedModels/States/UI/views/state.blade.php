<div>
    {{-- {!! $this->getStateLabel() !!} --}}

    {{--
        <x-chief-table::badge variant="grey">Draft</x-chief-table::badge>
        
        <x-chief-table::button wire:click="edit" variant="blue">
        <span>Publiceer</span>
        <x-chief::icon.chevron-down />
        </x-chief-table::button>
    --}}

    @if ($this->isAllowedToEdit())
        <x-chief-table::button wire:click="edit" variant="outline-blue">
            <span>{{ strip_tags($this->getStateLabel()) }}</span>
            <x-chief::icon.chevron-down />
        </x-chief-table::button>
    @endif
</div>
