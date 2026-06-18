import SortableGroup from './sortable';

/**
 * Make a specific DOM element and its children sortable.
 *
 * @param selector
 * @param container
 * @param options
 */
const initSortable = (selector, container = document, options = {}) => {
    for (const el of container.querySelectorAll(selector)) {
        new SortableGroup(el, {
            sortableGroupId: el.dataset.sortableGroup || 'models',
            endpoint: el.dataset.sortableEndpoint,
            nestedEndpoint: el.dataset.sortableNestedEndpoint,
            handle: '[data-sortable-handle]',
            isSorting: Object.hasOwn(el.dataset, 'sortableIsSorting'),
            sortableIdType: el.dataset.sortableIdType || 'int',
            ...options,
        });
    }
};

export default initSortable;
