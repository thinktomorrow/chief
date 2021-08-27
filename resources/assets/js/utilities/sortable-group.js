import IndexSorting from './sortable';

/**
 * Make a specific DOM element and its children sortable.
 *
 * @param selector
 * @param container
 * @param options
 */
const initSortableGroup = (selector, container = document, options = {}) => {
    Array.from(container.querySelectorAll(selector)).forEach((el) => {
        new IndexSorting({
            ...{
                sortableGroupEl: el,
                endpoint: el.getAttribute('data-sortable-endpoint'),
                handle: '[data-sortable-handle]',
                isSorting: true,
            },
            ...options,
        });
    });
};

export { initSortableGroup as default };
