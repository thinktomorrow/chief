import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sortableDirective = (el) => {
    el.sortable = Sortable.create(el, {
        draggable: '[x-sortable-item]',
        handle: '[x-sortable-handle]',
        dataIdAttr: 'x-sortable-item',
        animation: 250,
        ghostClass: 'opacity-25',
    });
};

export { sortableDirective as default };
