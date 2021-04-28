import Container from '../sidebar/Container';
import PanelsManager from '../sidebar/PanelsManager';
import IndexSorting from '../../utilities/sortable';
import SelectFragment from './selectFragment';
import EventBus from '../../utilities/EventBus';

/**
 * Sortable logic for fragment component
 */
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

    EventBus.subscribe('newFragmentPanelCreated', (panel) => {
        initSortable(selector, panel.el);

        new SelectFragment(panel.el);
    });

    EventBus.subscribe('fragmentsReloaded', () => {
        initSortable();
    });
};

const initFragments = () => {
    const fragmentsComponentElement = document.querySelector('[data-fragments-component]');
    const sidebarContainerElement = document.querySelector('#js-sidebar-container');

    // Do not trigger the sidebar script is DOM element isn't present
    if (!sidebarContainerElement || !fragmentsComponentElement) return;

    new SelectFragment(document);

    const fragmentPanelsManager = new PanelsManager(
        '[data-sidebar-fragments-edit]',
        new Container(sidebarContainerElement),
        {
            newPanelCreated: (panel) => {
                EventBus.publish('newFragmentPanelCreated', panel);
            },
            panelFormSubmitted: () => {
                EventBus.publish('fragmentPanelFormSubmitted');
            },
            events: {
                newFragmentSelectionPanelCreated: () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
                fragmentSelectionElementCreated: () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
                fragmentAdded: () => {
                    fragmentPanelsManager.replacePanelComponents();
                },
                fragmentsReloaded: () => {
                    fragmentPanelsManager.scanForPanelTriggers();
                },
            },
        }
    );

    /**
     * Fragments livewire components logic. Update the component on important changes
     */
    const livewireComponent = window.Livewire.find(fragmentsComponentElement.getAttribute('wire:id'));

    window.Livewire.on('fragmentsReloaded', () => {
        console.log('Reloading fragments ...');

        EventBus.publish('fragmentsReloaded');
    });

    EventBus.subscribe('fragmentAdded', () => {
        console.log(`Livewire component ${fragmentsComponentElement.getAttribute('wire:id')} reloaded`);

        livewireComponent.reload();
    });

    EventBus.subscribe('fragmentPanelFormSubmitted', () => {
        livewireComponent.reload();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initFragments();
    initSortable();
});

// /**
//  * Fragments JS
//  */
// document.addEventListener('DOMContentLoaded', () => {
//     const sidebarContainerEl = document.querySelector('#js-sidebar-container');
//     const componentEl = document.querySelector('[data-fragments-component]');

//     // Do not trigger the sidebar script is DOM element isn't present
//     if (!sidebarContainerEl || !componentEl) return;

//     new SelectFragment(document, '[data-fragments-container]');
//     new AddFragment(document);

//     const fragmentPanelsManager = new PanelsManager(
//         '[data-sidebar-fragments-edit]',
//         new Container(sidebarContainerEl),
//         {
//             newPanelCreated: (panel) => {
//                 EventBus.publish('newFragmentPanelCreated', panel);
//             },
//             panelFormSubmitted: () => {
//                 EventBus.publish('fragmentPanelFormSubmitted');
//             },
//             events: {
//                 fragmentSelectionElementCreated: () => {
//                     fragmentPanelsManager.scanForPanelTriggers();
//                 },
//                 // newFragmentSelectionPanelCreated: () => {
//                 //     // IS DIT NODIG???????
//                 //     fragmentPanelsManager.scanForPanelTriggers();
//                 // },
//                 fragmentAdded: () => {
//                     fragmentPanelsManager.replacePanelComponents();
//                 },
//                 fragmentsReloaded: () => {
//                     fragmentPanelsManager.scanForPanelTriggers();
//                 },
//             },
//         }
//     );

//     /**
//      * Fragments livewire components logic. Update the component on important changes
//      */
//     const livewireComponent = window.Livewire.find(componentEl.getAttribute('wire:id'));

//     function listen() {
//         console.log(`livewire comp. ${componentEl.getAttribute('wire:id')} reloaded.`);
//         livewireComponent.reload();
//     }

//     EventBus.subscribe('fragmentAdded', listen);

//     EventBus.subscribe('fragmentPanelFormSubmitted', () => {
//         livewireComponent.reload();
//     });

//     window.Livewire.on('fragmentsReloaded', () => {
//         console.log('reloading fragments');
//         EventBus.publish('fragmentsReloaded');
//     });

//     /**
//      * Sortable logic for fragment component
//      */
//     function initSortable(selector = '[data-sortable-fragments]', container = document, options = {}) {
//         Array.from(container.querySelectorAll(selector)).forEach((el) => {
//             new IndexSorting({
//                 ...{
//                     sortableGroupEl: el,
//                     endpoint: el.getAttribute('data-sortable-endpoint'),
//                     handle: '[data-sortable-handle]',
//                     isSorting: true,
//                 },
//                 ...options,
//             });
//         });
//     }

//     EventBus.subscribe('newFragmentPanelCreated', (panel) => {
//         initSortable('[data-sortable-fragments]', panel.el);

//         initFragments(panel);
//     });

//     EventBus.subscribe('fragmentsReloaded', () => {
//         initSortable();
//     });

//     initSortable();
// });
