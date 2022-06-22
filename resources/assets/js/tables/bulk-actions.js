/**
 * Handles bulk action checkboxes and highlighting
 * @param container container
 * @param parentCheckboxSelector Selector for parent (all items) checkbox
 * @param itemCheckboxSelector Selector for item checkbox
 */
class BulkActions {
    constructor(
        container = document,
        parentCheckboxSelector = '[data-bulk-all-checkbox]',
        itemCheckboxSelector = '[data-bulk-item-checkbox]'
    ) {
        this.container = container;

        if (!this.container) return;

        this.parentCheckbox = container.querySelector(parentCheckboxSelector);
        this.itemCheckboxes = Array.from(container.querySelectorAll(itemCheckboxSelector));

        this._init();
    }

    _init() {
        this.parentCheckbox.addEventListener('change', () => {
            this._updateItemCheckboxes();
        });

        this.itemCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                this._updateParentCheckbox();
            });
        });
    }

    _updateItemCheckboxes() {
        this.itemCheckboxes.forEach((checkbox) => {
            checkbox.checked = this.parentCheckbox.checked;
        });
    }

    _updateParentCheckbox() {
        // If all checkboxes are checked, set parent checkbox to checked
        if (this.itemCheckboxes.every((checkbox) => checkbox.checked)) {
            this.parentCheckbox.indeterminate = false;
            this.parentCheckbox.checked = true;
            return;
        }

        // If no checkboxes are checked, set parent checkbox to unchecked
        if (!this.itemCheckboxes.some((checkbox) => checkbox.checked)) {
            this.parentCheckbox.indeterminate = false;
            this.parentCheckbox.checked = false;
            return;
        }

        // In any other case, set parent checkbox to indeterminate
        this.parentCheckbox.indeterminate = true;
    }
}

const initBulkActions = () => {
    new BulkActions();
};

export { initBulkActions as default };
