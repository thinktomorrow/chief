<x-chief::dialog.drawer wired>
    @if ($isOpen)
        <x-slot name="header">
            <x-chief::dialog.drawer.header title="{{ $this->getTitle() }}">
                <x-slot name="backButton">
                    <x-chief::button
                        size="sm"
                        variant="grey"
                        type="button"
                        wire:click="close"
                        class="mt-[0.1875rem] shrink-0"
                    >
                        <x-chief::icon.arrow-left />
                    </x-chief::button>
                </x-slot>
            </x-chief::dialog.drawer.header>
        </x-slot>



        @if($this->isAllowedToSelectSites())
            @include('chief-models::livewire._partials.locale-selection')
        @endif

        @if(count($locales) > 0)

            <div class="mt-6">
                <x-chief::window>

                    <x-slot name="tabs">
                        <x-chief::window.tabs>
                            @foreach ($locales as $site)
                                <x-chief::window.tabs.item
                                    aria-controls="{{ $site }}"
                                    aria-selected="{{ $site === $scopedLocale }}"
                                    wire:key="locale-nav-{{ $site }}"
                                    wire:click="set('scopedLocale', '{{ $site }}')"
                                    :active="$site == $scopedLocale"
                                >
                                    {{ \Thinktomorrow\Chief\Sites\ChiefSites::shortName($site) }}
                                </x-chief::window.tabs.item>
                            @endforeach
                        </x-chief::window.tabs>
                    </x-slot>

                    @foreach ($this->getFields() as $field)
                        {{ $field }}
                    @endforeach
                </x-chief::window>

            </div>
        @else
            @foreach ($this->getFields() as $field)
                {{ $field }}
            @endforeach
        @endif



        <x-slot name="footer">
            <x-chief::dialog.drawer.footer>
                <x-chief::button wire:click="save" variant="blue" type="button">Bewaren</x-chief::button>
                <x-chief::button wire:click="close" type="button">Annuleer</x-chief::button>
            </x-chief::dialog.drawer.footer>
        </x-slot>

    @endif
</x-chief::dialog.drawer>
