<div x-data="tableFilters" class="flex items-start gap-2">
    @foreach ($this->getSecondaryFilters() as $filter)
        <div data-filter-key="{{ $filter->getKey() }}">
            {!! $filter->render() !!}
        </div>
    @endforeach

    <div>
        @if (count($this->getTertiaryFilters()) > 0)
            <x-chief-table::button
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
            </x-chief-table::button>
        @endif

        <x-chief::dialog.drawer id="table-filters-drawer" title="Meer filters">
            <div class="space-y-6">
                {{-- TODO: these filters shouldn't auto update on change, but use the submit button in drawer footer instead --}}
                @foreach ($this->getTertiaryFilters() as $filter)
                    <div class="space-y-2">
                        <div>
                            @if ($filter->getLabel())
                                <x-chief::input.label>
                                    {{ $filter->getLabel() }}
                                </x-chief::input.label>
                            @endif

                            @if ($filter->getDescription())
                                <x-chief::input.description>
                                    {!! $filter->getDescription() !!}
                                </x-chief::input.description>
                            @endif
                        </div>

                        {!! $filter->render() !!}
                    </div>
                @endforeach
            </div>

            <x-slot name="footer" class="flex items-center justify-start gap-2">
                <x-chief-table::button variant="blue" x-on:click="close">Bekijk resultaten</x-chief-table::button>
                <div>
                    <span>{{ $this->resultTotal }} resultaten gevonden</span>
                </div>
            </x-slot>
        </x-chief::dialog.drawer>
    </div>
</div>
