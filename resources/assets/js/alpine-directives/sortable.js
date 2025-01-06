import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sortableDirective = (el) => {
    el.sortable = Sortable.create(el, {
        dataIdAttr: 'x-sortable-item',
        draggable: '[x-sortable-item]',
        handle: '[x-sortable-handle]',
        group: el.getAttribute('x-sortable-group'),
        ghostClass: el.getAttribute('x-sortable-ghost-class') || 'sortable-ghost-class',
        dragClass: el.getAttribute('x-sortable-drag-class') || 'sortable-drag-class',
        animation: el.getAttribute('x-sortable-animation') || 150,
        swapThreshold: el.getAttribute('x-sortable-swap-threshold') || 0.65,
        fallbackOnBody: el.hasAttribute('x-sortable-fallback-on-body') || true,
    });
};

export { sortableDirective as default };
