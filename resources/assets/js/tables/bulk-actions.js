import _isEmpty from 'lodash/isEmpty';
/**
 * Handles bulk actions display, checkboxes and highlighting
 * @param container container
 * @param parentCheckboxSelector Selector for parent (all items) checkbox
 * @param itemCheckboxSelector Selector for item checkbox
 */
class BulkActions {
    constructor(container = document) {
        this.container = container;

        this.bulkActionsContainerSelector = '[data-bulk-actions-container]';
        this.bulkActionsCounterAttribute = 'data-bulk-actions-counter';
        this.parentCheckboxSelector = '[data-bulk-all-checkbox]';
        this.itemCheckboxSelector = '[data-bulk-item-checkbox]';
        this.bulkActionItemFieldSelector = '[data-bulk-action-item-field]';

        this.bulkActionsContainer = container.querySelector(this.bulkActionsContainerSelector);
        this.bulkActionsCounter = this.bulkActionsContainer.querySelector(`[${this.bulkActionsCounterAttribute}]`);

        if (!this.bulkActionsContainer || !this.bulkActionsCounter) return;

        this.parentCheckbox = container.querySelector(this.parentCheckboxSelector);
        this.itemCheckboxes = Array.from(container.querySelectorAll(this.itemCheckboxSelector));
        this.bulkActionItemFields = Array.from(container.querySelectorAll(this.bulkActionItemFieldSelector));

        if (!this.parentCheckbox || _isEmpty(this.itemCheckboxes)) return;

        this._init();
    }

    _init() {
        this.parentCheckbox.addEventListener('change', () => {
            this._updateItemCheckboxes();

            const count = this._getBulkActionsCount();

            this._updateBulkActionsCounter(count);
            this._syncBulkItemsFields();
        });

        this.itemCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                this._updateParentCheckbox();
                this.constructor._updateItemRowStyle(checkbox);

                const count = this._getBulkActionsCount();

                this._updateBulkActionsCounter(count);
                this._syncBulkItemsFields();
            });
        });
    }

    _updateItemCheckboxes() {
        this.itemCheckboxes.forEach((checkbox) => {
            checkbox.checked = this.parentCheckbox.checked;
            this.constructor._updateItemRowStyle(checkbox);
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

    static _updateItemRowStyle(checkbox) {
        // TODO: Make this selector dynamic.
        // Maybe by making a general TableRow class which will can passed to all other table classes (e.g BulkActions).
        const row = checkbox.closest('[data-table-row]');
        const highlightClass = 'bg-primary-50';

        if (checkbox.checked) {
            row.classList.add(highlightClass);
            return;
        }

        row.classList.remove(highlightClass);
    }

    _getBulkActionsCount() {
        let count = 0;

        for (let i = 0; i < this.itemCheckboxes.length; i++) {
            if (this.itemCheckboxes[i].checked) count++;
        }

        return count;
    }

    _updateBulkActionsCounter(count) {
        this._toggleBulkActionsContainer(count);

        this.bulkActionsCounter.innerHTML = count;
        this.bulkActionsCounter.setAttribute(this.bulkActionsCounterAttribute, count);
    }

    _toggleBulkActionsContainer(count) {
        if (count > 0) {
            this.bulkActionsContainer.classList.remove('hidden');
            return;
        }

        this.bulkActionsContainer.classList.add('hidden');
    }

    // Sync the selected table rows with the bulk action form fields.
    // This way the request has this selection as payload.
    _syncBulkItemsFields() {
        // Current selection
        const selectedValues = this._getSelectedValues();

        // Get all input elements of the target forms and populate each with the selected values
        this.bulkActionItemFields.forEach((el) => {
            el.value = JSON.stringify(selectedValues);
        });
    }

    _getSelectedValues() {
        return this.itemCheckboxes.filter((el) => el.checked).map((el) => el.value);
    }
}

const initBulkActions = (containerSelector = '[data-table-container]') => {
    const containers = Array.from(document.querySelectorAll(containerSelector));

    containers.forEach((container) => {
        new BulkActions(container);
    });
};

export { initBulkActions as default };
