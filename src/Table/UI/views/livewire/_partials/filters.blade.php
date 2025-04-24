<div x-data="tableFilters" class="flex items-start gap-2">
    @foreach ($this->getSecondaryFilters() as $filter)
        <div data-filter-key="{{ $filter->getKey() }}">
            {!! $filter->render() !!}
        </div>
    @endforeach

    <div>
        @if (count($this->getTertiaryFilters()) > 0)
            <x-chief::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-filters-drawer' })"
                variant="outline-white"
                class="relative"
            >
                <x-chief::icon.filter-edit />
                @if (($tertiaryFilterCount = $this->getTertiaryFilterCount()) > 0)
                    <div class="absolute -bottom-1.5 -right-1.5">
                        <div class="flex size-5 items-center justify-center rounded-full bg-primary-500">
                            <span class="text-xs font-medium text-white">
                                {{ $tertiaryFilterCount }}
                            </span>
                        </div>
                    </div>
                @endif
            </x-chief::button>
        @endif

        <x-chief::dialog.drawer id="table-filters-drawer" title="Meer filters">
            <div class="space-y-6">
                {{-- TODO: these filters shouldn't auto update on change, but use the submit button in drawer footer instead --}}
                @foreach ($this->getTertiaryFilters() as $filter)
                    <div class="space-y-2">
                        <div>
                            @if ($filter->getLabel())
                                <x-chief::form.label>
                                    {{ $filter->getLabel() }}
                                </x-chief::form.label>
                            @endif

                            @if ($filter->getDescription())
                                <x-chief::form.description>
                                    {!! $filter->getDescription() !!}
                                </x-chief::form.description>
                            @endif
                        </div>

                        {!! $filter->render() !!}
                    </div>
                @endforeach
            </div>

            <x-slot name="footer">
                <x-chief::dialog.drawer.footer>
                    <x-chief::button variant="blue" x-on:click="close">Bekijk resultaten</x-chief::button>
                    <span class="my-2 leading-5 text-grey-500">{{ $this->resultTotal }} resultaten gevonden</span>
                </x-chief::dialog.drawer.footer>
            </x-slot>
        </x-chief::dialog.drawer>
    </div>
</div>
