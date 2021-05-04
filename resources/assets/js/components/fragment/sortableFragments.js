import IndexSorting from '../../utilities/sortable';
import EventBus from '../../utilities/EventBus';

const initSortable = (selector = '[data-sortable-fragments]', container = document, options = {}) => {
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

    EventBus.subscribe('fragmentSidebarPanelCreated', (panelData) => {
        initSortable(selector, panelData.panel.el);
    });

    // window.Livewire.on('fragmentsReloaded', () => {
    //     initSortable();
    // });
};

export { initSortable };
