import Container from '../sidebar/Container';
import PanelsManager from '../sidebar/PanelsManager';
import IndexSorting from '../../utilities/sortable';
import AddFragment from './addFragment';
import SelectFragment from './selectFragment';
import EventBus from '../../utilities/EventBus';

/**
 * Fragments JS
 */
document.addEventListener('DOMContentLoaded', () => {
    const sidebarContainerEl = document.querySelector('#js-sidebar-container');
    const componentEl = document.querySelector('[data-fragments-component]');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerEl || !componentEl) return;

    new SelectFragment(document, '[data-fragments-container]');
    new AddFragment(document);

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
                'selection-element-created': () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
                'selection-panel-created': () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
                'fragment-add': () => {
                    fragmentPanelsManager.replacePanelComponents();
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
    const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

    function listen() {
        console.log(`livewire comp. ${componentEl.getAttribute('wire:id')} reloaded.`);
        livewireComponent.reload();
    }

    EventBus.subscribe('fragment-add', listen);

    EventBus.subscribe('fragments-submit-panel', () => {
        livewireComponent.reload();
    });

    window.Livewire.on('fragmentsReloaded', () => {
        console.log('reloading fragments');
        EventBus.publish('fragments-reloaded');
    });

    /**
     * Sortable logic for fragment component
     */
    function initSortable(selector = '[data-sortable-fragments]', container = document, options = {}) {
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

    EventBus.subscribe('fragments-reloaded', () => {
        initSortable();
    });

    initSortable();
});
