<div x-data="tableFilters" class="flex items-start gap-2">
    @foreach ($this->getVisibleFilters() as $filter)
        <div data-filter-key="{{ $filter->getKey() }}">
            {!! $filter->render() !!}
        </div>
    @endforeach

    <div>
        @if (count($this->getHiddenFilters()) > 0)
            <x-chief-table::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-filters-drawer' })"
                variant="tertiary"
                class="relative"
            >
                <x-chief::icon.filter-edit />
                @if (($hiddenFilterCount = $this->getHiddenFilterCount()) > 0)
                    <div class="absolute -bottom-1.5 -right-1.5">
                        <div class="flex size-5 items-center justify-center rounded-full bg-primary-500">
                            <span class="text-xs font-medium text-white">
                                {{ $hiddenFilterCount }}
                            </span>
                        </div>
                    </div>
                @endif
            </x-chief-table::button>
        @endif

        <x-chief::dialog.drawer id="table-filters-drawer" title="Meer filters">
            <div class="space-y-6">
                {{-- TODO: these filters shouldn't auto update on change, but use the submit button in drawer footer instead --}}
                @foreach ($this->getHiddenFilters() as $filter)
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
                <x-chief-table::button variant="primary" x-on:click="close">Bekijk resultaten</x-chief-table::button>
                <div>
                    <span>{{ $this->resultTotal }} resultaten gevonden</span>
                </div>
            </x-slot>
        </x-chief::dialog.drawer>
    </div>
</div>

<script>
    // TODO: reset filter positions and calculate width again on resize
    function tableFilters() {
        return {
            init() {
                this.$nextTick(() => {
                    this.moveLastFilterToDrawer();
                });

                window.addEventListener('resize', () => {
                    this.moveLastFilterToDrawer();
                });
            },
            getContentWidth(element) {
                const styles = window.getComputedStyle(element);
                const paddingLeft = parseFloat(styles.paddingLeft);
                const paddingRight = parseFloat(styles.paddingRight);
                return element.offsetWidth - paddingLeft - paddingRight;
            },
            moveLastFilterToDrawer() {
                const tableHeaderWidth = this.getContentWidth(document.getElementById('table-container-header'));
                const tableHeaderFiltersWidth = this.getContentWidth(this.$el);
                const tableHeaderSortersWidth = this.getContentWidth(
                    document.getElementById('table-container-header-sorters')
                );

                // Adding in 64px for the drawer button
                if (tableHeaderFiltersWidth > tableHeaderWidth - tableHeaderSortersWidth - 64) {
                    const visibleFilters = Array.from(document.querySelectorAll('[data-filter-key]'));
                    const lastFilter = visibleFilters[visibleFilters.length - 1];

                    this.$wire.hideFilter(lastFilter.getAttribute('data-filter-key'));

                    lastFilter.remove();

                    this.moveLastFilterToDrawer();
                }
            },
        };
    }
</script>
