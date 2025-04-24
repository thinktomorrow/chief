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
        const paddingLeft = parseFloat(styles.paddingLeft);
        const paddingRight = parseFloat(styles.paddingRight);
        return element.offsetWidth - paddingLeft - paddingRight;
    },
    moveLastFilterToDrawer() {
        const tableHeaderWidth = this.getContentWidth(document.getElementById('table-container-header'));
        const tableHeaderFiltersWidth = this.getContentWidth(this.$el);
        const tableHeaderSortersWidth = this.getContentWidth(document.getElementById('table-container-header-sorters'));

        // Adding in 64px for the drawer button
        if (tableHeaderFiltersWidth > tableHeaderWidth - tableHeaderSortersWidth - 64) {
            const visibleFilters = Array.from(document.querySelectorAll('[data-filter-key]'));
            const lastFilter = visibleFilters[visibleFilters.length - 1];

            this.$wire.setFilterAsTertiary(lastFilter.getAttribute('data-filter-key'));

            lastFilter.remove();

            this.moveLastFilterToDrawer();
        }
    },
});

export { tableFilters as default };
