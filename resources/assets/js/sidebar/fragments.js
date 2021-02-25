import Container from './Container';
import PanelsManager from './PanelsManager';
import { IndexSorting } from '../utilities/sortable';
import FragmentAdd from './fragmentAdd';
import FragmentNew from './fragmentNew';

/**
 * Fragments JS
 */
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEl = document.querySelector('[data-fragments-component]');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerEl || !componentEl) return;

    const livewireComponent = Livewire.find(componentEl.getAttribute('wire:id'));

    const fragmentNew = new FragmentNew(document, componentEl, function () {
        // Rescan for any DOM triggers
        // IDEA: replace callbacks with global event state?
        fragmentPanelsManager.scanForPanelTriggers();
        fragmentAdd.scanForTriggers();
    });
    fragmentNew.init();

    const fragmentAdd = new FragmentAdd(document, function (data) {
        livewireComponent.reload();
        fragmentAdd.scanForTriggers();
    });
    fragmentAdd.init();

    const fragmentPanelsManager = new PanelsManager(
        '[data-sidebar-fragments-edit]',
        new Container(sidebarContainerEl),
        function (panel) {
            console.log('New fragments panel ' + panel.id);

            fragmentNew.onNewPanel(panel);
            initSortable('[data-sortable-fragments]', panel.el);
        },
        function () {
            livewireComponent.reload();

            // TODO: set this in callback for when entire sidebar is closed.
            initSortable();
        }
    );

    fragmentPanelsManager.init();

    Livewire.on('fragmentsReloaded', () => {
        fragmentPanelsManager.scanForPanelTriggers();

        scanForFragmentSelectionTriggers();
    });

    function initSortable(selector = '[data-sortable-fragments]', container = document, options = {}) {
        // TODO: first remove existing sortable instances on these same selector els...
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
    }

    initSortable();
});
