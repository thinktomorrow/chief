import Bulkselect from '../alpine-directives/bulkselect';

describe('Bulkselect alpine Component', () => {
    let instance;
    let container;

    beforeEach(() => {
        document.body.innerHTML = `
            <div x-data="bulkselect({
                    showCheckboxes: true,
                    selection: [],
                    paginators: [],
                    tableHeaderCheckboxSelector: '#tableHeaderCheckbox'
                })">
                <input type="checkbox" id="tableHeaderCheckbox" />
                <input x-model="selection" type="checkbox" data-table-row-checkbox value="1" />
                <input x-model="selection" type="checkbox" data-table-row-checkbox value="2" />
                <input x-model="selection" type="checkbox" data-table-row-checkbox value="3" />
                <input x-model="selection" type="checkbox" data-table-row-checkbox value="4" />
            </div>
        `;

        container = document.body.firstElementChild;

        instance = Bulkselect({
            showCheckboxes: true,
            selection: [],
            paginators: [],
            tableHeaderCheckboxSelector: '#tableHeaderCheckbox',
        });

        instance.$el = container; // Mock Alpine.js $el

        // Manually mock $watch functionality
        instance.$watch = (property, callback) => {
            callback(property);
        };

        // Mock $nextTick to simulate DOM update cycle
        instance.$nextTick = (callback) => callback();

        instance.init(); // Initialize the component
    });

    it('initializes with the correct configuration', () => {
        expect(instance.showCheckboxes).toBe(true);
        expect(instance.selection).toEqual([]);
        expect(instance.paginators).toEqual([]);
    });

    it('selects all items on the current page when header checkbox is checked', () => {
        const headerCheckbox = container.querySelector('input[id="tableHeaderCheckbox"]');
        const checkboxes = container.querySelectorAll('[data-table-row-checkbox]');

        expect(instance.selection).toEqual([]); // No checkboxes are selected

        // Simulate checking header checkbox
        headerCheckbox.checked = true;
        const changeEvent = new Event('change');
        headerCheckbox.dispatchEvent(changeEvent);

        expect(instance.selection).toEqual(['1', '2', '3', '4']); // All checkboxes are selected
    });

    it('unselects all items on the current page when header checkbox is unchecked', () => {
        const headerCheckbox = container.querySelector('input[id="tableHeaderCheckbox"]');

        expect(instance.selection).toEqual([]);

        // Simulate selecting all items first
        headerCheckbox.checked = true;
        headerCheckbox.dispatchEvent(new Event('change'));

        expect(instance.selection).toEqual(['1', '2', '3', '4']); // All checkboxes are selected

        // Now uncheck the header checkbox
        headerCheckbox.checked = false;
        headerCheckbox.dispatchEvent(new Event('change'));

        expect(instance.selection).toEqual([]); // No items should be selected
    });

    it('sets indeterminate state when some but not all items are selected', () => {
        const headerCheckbox = container.querySelector('input[id="tableHeaderCheckbox"]');
        const rowCheckbox = container.querySelector('[data-table-row-checkbox][value="1"]');

        // Simulate checking one row checkbox
        rowCheckbox.checked = true;
        instance.selection.push('1');
        const changeEvent = new Event('change');
        rowCheckbox.dispatchEvent(changeEvent);

        // Manually trigger Alpine's indeterminate watcher
        instance.evaluateHeaderCheckboxState();

        // The header checkbox should be in an indeterminate state
        expect(headerCheckbox.indeterminate).toBe(true);
    });

    it('maintains selection across pages and updates when paginator changes', () => {
        const headerCheckbox = container.querySelector('input[id="tableHeaderCheckbox"]');

        // Simulate selecting some items on the first page
        instance.selection = ['1', '2'];

        // Simulate a page change by updating the paginator and page items
        instance.paginators = { page: 2 };

        // Fake trigger watch after pagination
        instance.setPageItems();
        instance.evaluateHeaderCheckboxState();

        // Ensure previous selection remains and header checkbox is updated
        expect(instance.selection).toEqual(['1', '2']); // Previous selections are maintained
    });
});
