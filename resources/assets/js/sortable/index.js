import Sortable from 'sortablejs';
import sortableDirective from '../alpine-directives/sortable';
import initSortable from './sortable-init';

initSortable('[data-sortable]');

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

window.Alpine.directive('sortable', sortableDirective);
