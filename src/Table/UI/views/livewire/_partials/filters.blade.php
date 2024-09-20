<div x-data="tableFilters" class="flex items-start gap-2">
    @foreach ($this->getVisibleFilters() as $filter)
        <div data-filter-key="{{ $filter->getKey() }}">
            {!! $filter->render() !!}
        </div>
    @endforeach

    <div>
        @if (count($this->getHiddenFilters()) > 0)
            <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-filters-drawer' })">
                <x-chief-table::button
                    color="white"
                    iconLeft='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none"> <path d="M13.2426 17.5C13.1955 17.8033 13.1531 18.0485 13.1164 18.2442C12.8876 19.4657 11.1555 20.2006 10.2283 20.8563C9.67638 21.2466 9.00662 20.782 8.9351 20.1778C8.79875 19.0261 8.54193 16.6864 8.26159 13.2614C8.23641 12.9539 8.08718 12.6761 7.85978 12.5061C5.37133 10.6456 3.59796 8.59917 2.62966 7.44869C2.32992 7.09255 2.2317 6.83192 2.17265 6.37282C1.97043 4.80082 1.86933 4.01482 2.33027 3.50742C2.79122 3.00002 3.60636 3.00002 5.23665 3.00002H16.768C18.3983 3.00002 19.2134 3.00002 19.6743 3.50742C19.8979 3.75348 19.9892 4.06506 20.001 4.50002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M20.8628 7.4392L21.5571 8.13157C22.1445 8.71735 22.1445 9.6671 21.5571 10.2529L17.9196 13.9486C17.6335 14.2339 17.2675 14.4263 16.8697 14.5003L14.6153 14.9884C14.2593 15.0655 13.9424 14.7503 14.0186 14.3951L14.4985 12.1598C14.5728 11.7631 14.7657 11.3981 15.0518 11.1128L18.7356 7.4392C19.323 6.85342 20.2754 6.85342 20.8628 7.4392Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
                />
            </button>
            @if ($this->areAnyHiddenFiltersActive())
                <span>BOLLEKE</span>
            @endif
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
