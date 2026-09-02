import Sortable from 'sortablejs';
import sortableDirective from '../alpine-directives/sortable';
import initSortable from './sortable-init';

initSortable('[data-sortable]');

window.Sortable = Sortable;

/**
 * Based on the Filament sortable.js livewire integration
 */
let hasRegisteredLivewireSortableDirective = false;

const registerLivewireSortableDirective = () => {
    if (hasRegisteredLivewireSortableDirective || !window.Livewire?.directive) {
        return;
    }

    hasRegisteredLivewireSortableDirective = true;

    window.Livewire.directive('sortable', ({ el, cleanup }) => {
        el.sortable = Sortable.create(el, {
            draggable: String.raw`[wire\:sortable\.item]`,
            handle: String.raw`[wire\:sortable\.handle]`,
            dataIdAttr: 'wire:sortable.item',
            ghostClass: 'bg-primary-50',
            dragClass: 'bg-white',
        });

        cleanup(() => el.sortable.destroy());
    });
};

registerLivewireSortableDirective();
document.addEventListener('livewire:init', registerLivewireSortableDirective);

window.Alpine.directive('sortable', sortableDirective);
