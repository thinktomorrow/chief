import SortableGroup from './sortable';

/**
 * Make a specific DOM element and its children sortable.
 *
 * @param selector
 * @param container
 * @param options
 */
const initSortable = (selector, container = document, options = {}) => {
    Array.from(container.querySelectorAll(selector)).forEach((el) => {
        new SortableGroup({
            ...{
                sortableGroupEl: el,
                sortableGroupId: el.getAttribute('data-sortable-group') || 'models',
                endpoint: el.getAttribute('data-sortable-endpoint'),
                nestedEndpoint: el.getAttribute('data-sortable-nested-endpoint'),
                handle: '[data-sortable-handle]',
                isSorting: el.hasAttribute('data-sortable-is-sorting'),
                sortableIdType: el.getAttribute('data-sortable-id-type') || 'int',
            },
            ...options,
        });
    });
};

export { initSortable as default };
