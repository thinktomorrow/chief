import Sortable from 'sortablejs';

window.Sortable = Sortable;

/**
 * Based on the Filament sortable.js livewire integration
 */
window.Livewire.directive('sortable', ({ el }) => {
    el.sortable = Sortable.create(el, {
        draggable: '[wire\\:sortable\\.item]',
        handle: '[wire\\:sortable\\.handle]',
        dataIdAttr: 'wire:sortable.item',
        ghostClass: 'bg-primary-50',
        dragClass: 'bg-white',
    });
});
