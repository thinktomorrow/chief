<div x-data="tableFilters" class="flex items-start gap-2">
    @foreach ($this->getVisibleFilters() as $filter)
        <div data-filter-key="{{ $filter->getKey() }}">
            {!! $filter->render() !!}
        </div>
    @endforeach

    <div>
        @if (count($this->getHiddenFilters()) > 0)
            <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-filters-drawer' })">
                {{-- TODO(tijs): check this layout --}}
                <x-chief-table::button
                    color="white"
                    @class(['border border-primary-500 bg-primary-50' => $this->areAnyHiddenFiltersActive()])
                >
                    <x-chief::icon.filter-edit />
                </x-chief-table::button>
            </button>
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
                <x-chief-table::button color="primary" x-on:click="close">Bekijk resultaten</x-chief-table::button>
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
