<div>
    <x-chief::dialog.drawer wired>
        @if ($isOpen)
            <x-slot name="header">
                <x-chief::dialog.drawer.header
                    :title="match ($kind) { 'event' => 'Historiekdetails', 'model' => 'Wijzigingsdetails', 'rich' => 'Historische inhoud' }"
                >
                    <x-slot name="backButton">
                        <x-chief::button type="button" size="sm" variant="grey" wire:click="close" aria-label="Sluiten">
                            <x-chief::icon.arrow-left />
                        </x-chief::button>
                    </x-slot>
                </x-chief::dialog.drawer.header>
            </x-slot>

            @if ($kind === 'event')
                @include ('chief-audit::event-details')
            @elseif ($kind === 'model')
                @include ('chief-audit::details')
            @elseif ($kind === 'rich')
                @include ('chief-audit::rich-data')
            @endif
        @endif
    </x-chief::dialog.drawer>
</div>
