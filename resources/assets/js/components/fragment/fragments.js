import Container from '../sidebar/Container';
import PanelsManager from '../sidebar/PanelsManager';
import { IndexSorting } from '../../utilities/sortable';
import FragmentAdd from './fragmentAdd';
import FragmentNew from './fragmentNew';
import EventBus from '../../utilities/EventBus';

/**
 * Fragments JS
 */
document.addEventListener('DOMContentLoaded', function () {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEl = document.querySelector('[data-fragments-component]');
    const fragmentsContainerEl = componentEl.querySelector('[data-fragments-container]');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerEl || !componentEl || !fragmentsContainerEl) return;

    new FragmentNew(document, fragmentsContainerEl);
    new FragmentAdd(document);

    const fragmentPanelsManager = new PanelsManager(
        '[data-sidebar-fragments-edit]',
        new Container(sidebarContainerEl),
        {
            onNewPanel: (panel) => {
                EventBus.publish('fragments-new-panel', panel);
            },
            onSubmitPanel: () => {
                EventBus.publish('fragments-submit-panel');
            },
            events: {
                'fragment-new': () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
                'fragments-reloaded': () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
            },
        }
    );

    /**
     * Fragments livewire components logic. Update the component on important changes
     */
    const livewireComponent = Livewire.find(componentEl.getAttribute('wire:id'));

    EventBus.subscribe('fragment-add', () => {
        livewireComponent.reload();
    });

    EventBus.subscribe('fragments-submit-panel', () => {
        livewireComponent.reload();
    });

    Livewire.on('fragmentsReloaded', () => {
        EventBus.publish('fragments-reloaded');
    });

    /**
     * Sortable logic for fragment component
     */
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

    EventBus.subscribe('fragments-new-panel', (panel) => {
        initSortable('[data-sortable-fragments]', panel.el);
    });

    EventBus.subscribe('fragments-submit-panel', () => {
        initSortable();
    });

    initSortable();
});
