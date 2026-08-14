const tableFilters = () => ({
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
        const paddingLeft = Number.parseFloat(styles.paddingLeft);
        const paddingRight = Number.parseFloat(styles.paddingRight);
        return element.offsetWidth - paddingLeft - paddingRight;
    },
    moveLastFilterToDrawer() {
        // Scope lookups to this table: a page can hold multiple tables, each rendering
        // the same header/sorters ids.
        const tableHeader = this.$el.closest('#table-container-header');
        const tableHeaderSorters = tableHeader?.querySelector('#table-container-header-sorters');

        if (!tableHeader || !tableHeaderSorters) {
            return;
        }

        const tableHeaderWidth = this.getContentWidth(tableHeader);
        const tableHeaderFiltersWidth = this.getContentWidth(this.$el);
        const tableHeaderSortersWidth = this.getContentWidth(tableHeaderSorters);

        // Adding in 64px for the drawer button
        if (tableHeaderFiltersWidth > tableHeaderWidth - tableHeaderSortersWidth - 64) {
            const visibleFilters = [...this.$el.querySelectorAll('[data-filter-key]')];
            const lastFilter = visibleFilters.at(-1);

            // Nothing left to move: bail out instead of recursing forever.
            if (!lastFilter) {
                return;
            }

            this.$wire.setFilterAsTertiary(lastFilter.dataset.filterKey);

            lastFilter.remove();

            this.moveLastFilterToDrawer();
        }
    },
});

export default tableFilters;
