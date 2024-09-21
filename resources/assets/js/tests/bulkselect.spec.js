import Bulkselect from '../alpine-directives/bulkselect';

describe('Bulkselect alpine Component', () => {
    let instance;
    let container;

    beforeEach(() => {
        document.body.innerHTML = `
            <div>
                <input type="checkbox" x-ref="tableHeaderCheckbox" />
                <input type="checkbox" data-table-row-checkbox value="1" />
                <input type="checkbox" data-table-row-checkbox value="2" />
                <input type="checkbox" data-table-row-checkbox value="3" />
                <input type="checkbox" data-table-row-checkbox value="4" />
            </div>
        `;

        container = document.body.firstElementChild;

        instance = Bulkselect({
            showCheckboxes: true,
            selection: [],
            paginators: [],
        });

        instance.$el = container; // Mock Alpine.js $el

        // Mock the Alpine.js $refs manually by setting the tableHeaderCheckbox
        instance.$refs = {
            tableHeaderCheckbox: container.querySelector('input[x-ref="tableHeaderCheckbox"]'),
        };

        // Manually mock $watch functionality
        instance.$watch = (property, callback) => {
            callback(property); // Mock the $watch function to immediately execute the callback
        };

        // Mock $nextTick to simulate DOM update cycle
        instance.$nextTick = (callback) => callback();

        instance.init(); // Initialize the component
    });

    it('initializes with the correct configuration', () => {
        // expect(instance.showCheckboxes).toBe(true);
        // expect(instance.selection).toEqual([]);
        // expect(instance.paginators).toEqual([]);
    });

    it.only('selects all items on the current page when header checkbox is checked', () => {
        const headerCheckbox = container.querySelector('input[x-ref="tableHeaderCheckbox"]');
        const checkboxes = container.querySelectorAll('[data-table-row-checkbox]');

        // Simulate checking header checkbox
        headerCheckbox.checked = true;
        const changeEvent = new Event('change');
        headerCheckbox.dispatchEvent(changeEvent);

        expect(instance.selection).toEqual(['1', '2', '3', '4']); // All checkboxes are selected

        checkboxes.forEach((checkbox) => {
            expect(checkbox.checked).toBe(true); // The DOM should reflect selection
        });
    });

    it('unselects all items on the current page when header checkbox is unchecked', () => {
        const headerCheckbox = container.querySelector('input[x-ref="tableHeaderCheckbox"]');

        // Simulate selecting all items first
        headerCheckbox.checked = true;
        headerCheckbox.dispatchEvent(new Event('change'));

        // Now uncheck the header checkbox
        headerCheckbox.checked = false;
        headerCheckbox.dispatchEvent(new Event('change'));

        expect(instance.selection).toEqual([]); // No items should be selected
    });

    it('sets indeterminate state when some but not all items are selected', () => {
        const headerCheckbox = container.querySelector('input[x-ref="tableHeaderCheckbox"]');
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
        const headerCheckbox = container.querySelector('input[x-ref="tableHeaderCheckbox"]');

        // Simulate selecting some items on the first page
        instance.selection = ['1', '2'];

        // Simulate a page change by updating the paginator and page items
        instance.paginators = { page: 2 };
        instance.$nextTick(() => {
            instance.setPageItems();
            instance.evaluateHeaderCheckboxState();
        });

        // Ensure previous selection remains and header checkbox is updated
        expect(headerCheckbox.checked).toBe(false); // New page, so not all items are selected
        expect(instance.selection).toEqual(['1', '2']); // Previous selections are maintained
    });

    it('selects all items across pages', () => {
        const headerCheckbox = container.querySelector('input[x-ref="tableHeaderCheckbox"]');

        // Simulate "select all" action across multiple pages
        instance.selection = Array.from({ length: 100 }, (_, i) => (i + 1).toString());

        headerCheckbox.checked = true;
        headerCheckbox.dispatchEvent(new Event('change'));

        expect(instance.selection.length).toBe(100); // Ensure all items are selected
    });
});
