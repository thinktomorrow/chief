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
        const tableHeaderWidth = this.getContentWidth(document.querySelector('#table-container-header'));
        const tableHeaderFiltersWidth = this.getContentWidth(this.$el);
        const tableHeaderSortersWidth = this.getContentWidth(document.querySelector('#table-container-header-sorters'));

        // Adding in 64px for the drawer button
        if (tableHeaderFiltersWidth > tableHeaderWidth - tableHeaderSortersWidth - 64) {
            const visibleFilters = [...document.querySelectorAll('[data-filter-key]')];
            const lastFilter = visibleFilters.at(-1);

            this.$wire.setFilterAsTertiary(lastFilter.dataset.filterKey);

            lastFilter.remove();

            this.moveLastFilterToDrawer();
        }
    },
});

export default tableFilters;
