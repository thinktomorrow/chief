import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sortableDirective = (el) => {
    console.log(el);
    el.sortable = Sortable.create(el, {
        draggable: '[x-sortable-item]',
        handle: '[x-sortable-handle]',
        dataIdAttr: 'x-sortable-item',
    });
};

export { sortableDirective as default };
