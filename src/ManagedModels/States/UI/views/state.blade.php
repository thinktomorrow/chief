<div>
    {{--
        @if ($this->isAllowedToEdit())
        <x-chief-table::button wire:click="edit" variant="outline-blue">
        <span>{{ strip_tags($this->getStateLabel()) }}</span>
        <x-chief::icon.chevron-down />
        </x-chief-table::button>
        @endif
    --}}

    <x-chief-table::button variant="blue">
        <span>Publiceer</span>
        <x-chief::icon.solid.sent />
    </x-chief-table::button>
</div>
