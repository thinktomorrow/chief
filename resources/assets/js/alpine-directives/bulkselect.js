const Bulkselect = (config) => ({
    showCheckboxes: config.showCheckboxes || true,
    selection: config.selection || [],
    paginators: config.paginators || [],
    isAllSelectedOnPage: false,
    isIndeterminateOnPage: false, // One or more but not all selected on page

    init() {
        // No longer header checkbox work after filtering
        // when total changed after filtering does not work ... best to entangle??

        this.$refs.tableHeaderCheckbox.addEventListener('change', (event) => {
            if (event.target.checked) {
                const checkboxes = document.querySelectorAll('[data-table-row-checkbox]');

                // Merge with current selection and make sure they are unique
                this.selection = [
                    ...this.selection,
                    ...Array.from(checkboxes).map((checkbox) => checkbox.value),
                ].filter((value, index, self) => self.indexOf(value) === index);
            } else {
                // Remove all items from current page from selection
                this.selection = this.selection.filter(
                    (item) => !this.pageItems.some((pageItem) => pageItem.toString() === item.toString())
                );
            }
        });

        this.$watch('selection', () => {
            this.evaluateHeaderCheckboxState();
        });

        this.$watch('isIndeterminateOnPage', (value) => {
            this.$refs.tableHeaderCheckbox.indeterminate = value;
        });

        this.$watch('isAllSelectedOnPage', (value) => {
            this.$refs.tableHeaderCheckbox.checked = value;
        });

        this.$watch('paginators', () => {
            this.$nextTick(() => {
                this.setPageItems();
                this.evaluateHeaderCheckboxState();
            });
        });

        // On initial load
        this.$nextTick(() => {
            this.setPageItems();
            this.evaluateHeaderCheckboxState();
        });
    },
    getPageItems() {
        return this.pageItems;
    },
    setPageItems() {
        this.pageItems = Array.from(this.$el.querySelectorAll('[data-table-row-checkbox]')).map(
            (checkbox) => checkbox.value
        );
    },
    getSelectedPageItems() {
        return this.pageItems.filter((item) =>
            this.selection.some((selectedItem) => selectedItem.toString() === item.toString())
        );
    },
    evaluateHeaderCheckboxState() {
        const pageItems = this.getPageItems();
        const selectedPageItems = this.getSelectedPageItems();

        if (
            pageItems.every((item) =>
                this.selection.some((selectedItem) => selectedItem.toString() === item.toString())
            )
        ) {
            this.isAllSelectedOnPage = true;
        } else {
            this.isAllSelectedOnPage = false;
        }

        if (selectedPageItems.length === pageItems.length || selectedPageItems.length === 0) {
            this.isIndeterminateOnPage = false;
        } else {
            this.isIndeterminateOnPage = true;
        }
    },
});

export { Bulkselect as default };
