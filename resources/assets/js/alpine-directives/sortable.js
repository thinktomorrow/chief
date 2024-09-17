import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sortableDirective = (el) => {
    el.sortable = Sortable.create(el, {
        draggable: '[x-sortable-item]',
        handle: '[x-sortable-handle]',
        dataIdAttr: 'x-sortable-item',
        group: el.getAttribute('x-sortable-group'),
        ghostClass: el.getAttribute('x-sortable-ghost-class') || 'sortable-ghost-class',
        dragClass: el.getAttribute('x-sortable-drag-class') || 'sortable-drag-class',
        animation: el.getAttribute('x-sortable-animation') || 150,
    });
};

export { sortableDirective as default };
